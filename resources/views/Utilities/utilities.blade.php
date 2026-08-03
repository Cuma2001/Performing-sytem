{{-- resources/views/admin/utility/master-upload.blade.php --}}
@extends('layouts.app')

@section('title', 'Master Spreadsheet Upload Utility')
@section('page-title', 'Master Data Upload Utility')
@section('page-subtitle', 'Bulk upload sales records, commissions, or employee data')

@section('styles')
<style>
    /* Additional custom styles for the upload utility */
    .upload-container {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .drop-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 1rem;
        padding: 2.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8fafc;
    }
    .drop-zone:hover, .drop-zone.drag-over {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    .preview-table {
        max-height: 400px;
        overflow-y: auto;
        font-size: 0.875rem;
    }
    .preview-table table {
        width: 100%;
        border-collapse: collapse;
    }
    .preview-table th {
        background: #f1f5f9;
        padding: 0.75rem;
        position: sticky;
        top: 0;
        font-weight: 600;
    }
    .preview-table td {
        padding: 0.5rem 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .validation-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .validation-success {
        background: #d1fae5;
        color: #065f46;
    }
    .validation-error {
        background: #fee2e2;
        color: #991b1b;
    }
    .validation-warning {
        background: #fed7aa;
        color: #9a3412;
    }
    .progress-bar {
        transition: width 0.3s ease;
    }
    .mapping-card {
        background: #f8fafc;
        border-radius: 1rem;
        padding: 1rem;
        margin-top: 1rem;
    }
    .file-info {
        background: #f1f5f9;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <!-- Upload Section -->
    <div class="upload-container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1"><i class="fas fa-upload me-2 text-primary"></i>Upload Master Spreadsheet</h4>
                        <p class="text-muted mb-0">Supported formats: .xlsx, .xls, .csv | Max size: 10MB</p>
                    </div>
                    <div>
                        <a href="" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download me-1"></i> Download Template
                        </a>
                    </div>
                </div>

                <!-- Upload Form -->
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="drop-zone" id="dropZone">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                        <h5>Drag & Drop your file here</h5>
                        <p class="text-muted">or click to browse</p>
                        <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" style="display: none;">
                        <button type="button" class="btn btn-primary mt-2" onclick="document.getElementById('fileInput').click()">
                            <i class="fas fa-folder-open me-1"></i> Select File
                        </button>
                    </div>

                    <!-- Upload Type Selection -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Upload Type *</label>
                            <select name="upload_type" id="uploadType" class="form-select" required>
                                <option value="">Select upload type...</option>
                                <option value="sales_bulk">Sales Records Bulk Upload</option>
                                <option value="commission_bulk">Commission Data Bulk Upload</option>
                                <option value="employee_bulk">Employee Data Bulk Upload</option>
                                <option value="target_bulk">Targets Bulk Upload</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Auto-verify records?</label>
                            <select name="auto_verify" class="form-select">
                                <option value="0">Manual verification required</option>
                                <option value="1">Auto-verify all valid records</option>
                            </select>
                        </div>
                    </div>
                </form>

                <!-- File Info Display -->
                <div id="fileInfo" class="file-info" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-excel text-success me-2"></i>
                            <strong id="fileName"></strong>
                            <span class="text-muted ms-2" id="fileSize"></span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile()">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                </div>

                <!-- Column Mapping Section (appears after file load) -->
                <div id="mappingSection" style="display: none;">
                    <div class="mapping-card">
                        <h6 class="fw-semibold mb-3"><i class="fas fa-code-branch me-2"></i>Column Mapping Preview</h6>
                        <div id="mappingPreview"></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4" id="actionButtons" style="display: none;">
                    <button type="button" class="btn btn-success" onclick="validateAndPreview()">
                        <i class="fas fa-check-circle me-1"></i> Validate & Preview
                    </button>
                    <button type="button" class="btn btn-primary ms-2" onclick="processUpload()" id="processBtn">
                        <i class="fas fa-upload me-1"></i> Process Upload
                    </button>
                    <button type="button" class="btn btn-secondary ms-2" onclick="clearAll()">
                        <i class="fas fa-eraser me-1"></i> Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div id="progressSection" style="display: none;">
        <div class="alert alert-info">
            <div class="d-flex align-items-center mb-2">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                <strong>Processing your upload...</strong>
            </div>
            <div class="progress" style="height: 20px;">
                <div id="uploadProgress" class="progress-bar progress-bar-striped progress-bar-animated"
                     style="width: 0%">0%</div>
            </div>
            <div id="progressDetails" class="mt-2 small text-muted"></div>
        </div>
    </div>

    <!-- Validation Results Section -->
    <div id="validationSection" style="display: none;">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Validation Results</h5>
            </div>
            <div class="card-body">
                <div id="validationSummary"></div>
                <div id="validationDetails" class="preview-table mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Recent Uploads History -->
    <div class="upload-container mt-4">
        <h5 class="mb-3"><i class="fas fa-history me-2"></i>Recent Uploads History</h5>
        <div class="table-responsive">
            <table class="table table-hover" id="uploadsHistoryTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Filename</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Records</th>
                        <th>Success/Failed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Loading recent uploads...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Hidden modal for error details -->
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Upload Errors</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="errorModalBody">
                <!-- Error details will be injected here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="downloadErrorLog()">
                    <i class="fas fa-download me-1"></i> Download Error Log
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let selectedFile = null;
let uploadId = null;
let validationData = null;

// Drop zone handling
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');

dropZone.addEventListener('click', () => fileInput.click());
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('drag-over');
});
dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('drag-over');
});
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('drag-over');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleFile(files[0]);
    }
});

fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        handleFile(e.target.files[0]);
    }
});

function handleFile(file) {
    const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                        'text/csv'];
    const maxSize = 10 * 1024 * 1024; // 10MB

    if (!validTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/i)) {
        showError('Invalid file type. Please upload .xlsx, .xls, or .csv files only.');
        return;
    }

    if (file.size > maxSize) {
        showError('File size exceeds 10MB limit.');
        return;
    }

    selectedFile = file;
    document.getElementById('fileName').innerText = file.name;
    document.getElementById('fileSize').innerText = formatFileSize(file.size);
    document.getElementById('fileInfo').style.display = 'block';
    document.getElementById('actionButtons').style.display = 'block';

    // Preview file data
    previewFileData(file);
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function previewFileData(file) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('preview_only', '1');
    formData.append('_token', '{{ csrf_token() }}');

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayMappingPreview(data.preview);
            document.getElementById('mappingSection').style.display = 'block';
        } else {
            showError(data.message || 'Failed to preview file');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Error previewing file. Please check the console.');
    });
}

function displayMappingPreview(preview) {
    const container = document.getElementById('mappingPreview');
    if (preview.headers && preview.headers.length) {
        let html = '<div class="table-responsive"><table class="table table-sm table-bordered">';
        html += '<thead><tr><th>Column Index</th><th>Column Name</th><th>Sample Data</th><th>Suggested Mapping</th></tr></thead><tbody>';

        preview.headers.forEach((header, idx) => {
            const sample = preview.sampleRows && preview.sampleRows[0] ? preview.sampleRows[0][idx] : '';
            html += `<tr>
                        <td>${idx + 1}</td>
                        <td><strong>${escapeHtml(header)}</strong></td>
                        <td><small>${escapeHtml(String(sample).substring(0, 50))}</small></td>
                        <td>
                            <select class="form-select form-select-sm mapping-select" data-col="${idx}">
                                <option value="">-- Ignore --</option>
                                <option value="employee_code">Employee Code</option>
                                <option value="employee_name">Employee Name</option>
                                <option value="invoice_number">Invoice Number</option>
                                <option value="sale_date">Sale Date</option>
                                <option value="amount">Amount/Sales</option>
                                <option value="quantity">Quantity</option>
                                <option value="product_code">Product Code</option>
                                <option value="region">Region</option>
                                <option value="store">Store</option>
                                <option value="commission_rate">Commission Rate</option>
                                <option value="payment_status">Payment Status</option>
                            </select>
                        </td>
                    </tr>`;
        });
        html += '</tbody></table></div>';
        container.innerHTML = html;
    } else {
        container.innerHTML = '<div class="alert alert-warning">No headers detected in the file.</div>';
    }
}

function validateAndPreview() {
    const uploadType = document.getElementById('uploadType').value;
    if (!uploadType) {
        showError('Please select an upload type');
        return;
    }

    if (!selectedFile) {
        showError('Please select a file first');
        return;
    }

    const formData = new FormData();
    formData.append('file', selectedFile);
    formData.append('upload_type', uploadType);
    formData.append('auto_verify', document.querySelector('[name="auto_verify"]').value);
    formData.append('_token', '{{ csrf_token() }}');

    // Get column mappings
    const mappings = {};
    document.querySelectorAll('.mapping-select').forEach(select => {
        if (select.value) {
            mappings[select.dataset.col] = select.value;
        }
    });
    formData.append('column_mappings', JSON.stringify(mappings));

    showProgress('Validating file data...', 30);

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideProgress();
        if (data.success) {
            validationData = data;
            displayValidationResults(data);
            document.getElementById('validationSection').style.display = 'block';
            document.getElementById('processBtn').disabled = false;

            // Show success notification
            showNotification('Validation completed!', 'success');
        } else {
            showError(data.message || 'Validation failed');
            document.getElementById('processBtn').disabled = true;
        }
    })
    .catch(error => {
        hideProgress();
        showError('Validation error: ' + error.message);
        document.getElementById('processBtn').disabled = true;
    });
}

function displayValidationResults(data) {
    const summaryDiv = document.getElementById('validationSummary');
    let summaryHtml = `
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="alert alert-success text-center">
                    <h4>${data.total_records || 0}</h4>
                    <small>Total Records</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-info text-center">
                    <h4>${data.valid_records || 0}</h4>
                    <small>Valid Records</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-warning text-center">
                    <h4>${data.warning_records || 0}</h4>
                    <small>Warnings</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-danger text-center">
                    <h4>${data.error_records || 0}</h4>
                    <small>Errors</small>
                </div>
            </div>
        </div>
    `;

    if (data.validation_errors && data.validation_errors.length > 0) {
        summaryHtml += `<div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Validation Issues Found:</strong> Please review the errors below before proceeding.
        </div>`;

        let errorsHtml = '<table class="table table-sm"><thead><tr><th>Row</th><th>Field</th><th>Error Message</th></tr></thead><tbody>';
        data.validation_errors.forEach(err => {
            errorsHtml += `<tr>
                <td>${err.row || 'N/A'}</td>
                <td>${err.field || 'General'}</td>
                <td class="text-danger">${escapeHtml(err.message)}</td>
            </tr>`;
        });
        errorsHtml += '</tbody></table>';
        document.getElementById('validationDetails').innerHTML = errorsHtml;
    } else {
        document.getElementById('validationDetails').innerHTML = '<div class="alert alert-success">✓ All records passed validation! Ready to process.</div>';
    }

    summaryDiv.innerHTML = summaryHtml;
}

function processUpload() {
    if (!validationData) {
        showError('Please validate the file first');
        return;
    }

    if (!confirm('Are you sure you want to process this upload? This will insert/update records in the database.')) {
        return;
    }

    const formData = new FormData();
    formData.append('file', selectedFile);
    formData.append('upload_type', document.getElementById('uploadType').value);
    formData.append('auto_verify', document.querySelector('[name="auto_verify"]').value);
    formData.append('_token', '{{ csrf_token() }}');

    // Get mappings
    const mappings = {};
    document.querySelectorAll('.mapping-select').forEach(select => {
        if (select.value) {
            mappings[select.dataset.col] = select.value;
        }
    });
    formData.append('column_mappings', JSON.stringify(mappings));

    showProgress('Processing upload...', 0);
    document.getElementById('processBtn').disabled = true;

    // Simulate progress updates
    let progress = 0;
    const progressInterval = setInterval(() => {
        if (progress < 90) {
            progress += 10;
            updateProgress(progress, 'Processing records...');
        }
    }, 500);

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(progressInterval);
        updateProgress(100, 'Completed!');

        setTimeout(() => {
            hideProgress();
            if (data.success) {
                showNotification(`Upload completed! ${data.processed_records || 0} records processed.`, 'success');
                loadUploadHistory();
                clearAll();

                // Show detailed results
                if (data.results) {
                    displayResultsModal(data.results);
                }
            } else {
                showError(data.message || 'Processing failed');
                if (data.error_log) {
                    displayErrorModal(data.error_log);
                }
            }
            document.getElementById('processBtn').disabled = false;
        }, 500);
    })
    .catch(error => {
        clearInterval(progressInterval);
        hideProgress();
        showError('Processing error: ' + error.message);
        document.getElementById('processBtn').disabled = false;
    });
}

function showProgress(message, percent) {
    document.getElementById('progressSection').style.display = 'block';
    document.getElementById('progressDetails').innerHTML = message;
    updateProgress(percent, message);
}

function updateProgress(percent, message) {
    const progressBar = document.getElementById('uploadProgress');
    progressBar.style.width = percent + '%';
    progressBar.innerHTML = percent + '%';
    document.getElementById('progressDetails').innerHTML = message;
}

function hideProgress() {
    document.getElementById('progressSection').style.display = 'none';
    document.getElementById('uploadProgress').style.width = '0%';
}

function loadUploadHistory() {
    fetch('')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#uploadsHistoryTable tbody');
            if (data.uploads && data.uploads.length > 0) {
                tbody.innerHTML = data.uploads.map(upload => `
                    <tr>
                        <td>${new Date(upload.created_at).toLocaleString()}</td>
                        <td>${escapeHtml(upload.original_filename)}</td>
                        <td><span class="badge bg-secondary">${upload.upload_type}</span></td>
                        <td><span class="badge bg-${getStatusColor(upload.status)}">${upload.status}</span></td>
                        <td>${upload.total_records || 0}</td>
                        <td>${upload.success_records || 0}/${upload.failed_records || 0}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" onclick="viewUploadDetails(${upload.id})">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No uploads found</td></tr>';
            }
        })
        .catch(error => console.error('Error loading history:', error));
}

function getStatusColor(status) {
    const colors = {
        'pending': 'warning',
        'processing': 'info',
        'completed': 'success',
        'failed': 'danger',
        'cancelled': 'secondary'
    };
    return colors[status] || 'secondary';
}

function clearFile() {
    selectedFile = null;
    fileInput.value = '';
    document.getElementById('fileInfo').style.display = 'none';
    document.getElementById('mappingSection').style.display = 'none';
    document.getElementById('validationSection').style.display = 'none';
    document.getElementById('actionButtons').style.display = 'none';
}

function clearAll() {
    clearFile();
    document.getElementById('uploadType').value = '';
    document.getElementById('validationSection').style.display = 'none';
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonColor: '#d33'
    });
}

function showNotification(message, type = 'success') {
    Swal.fire({
        icon: type,
        title: type === 'success' ? 'Success' : 'Information',
        text: message,
        timer: 3000,
        showConfirmButton: false
    });
}

function displayResultsModal(results) {
    let html = `<div class="mb-3">
                    <strong>Processed:</strong> ${results.processed || 0}<br>
                    <strong>Successful:</strong> ${results.successful || 0}<br>
                    <strong>Failed:</strong> ${results.failed || 0}
                </div>`;
    if (results.errors && results.errors.length) {
        html += '<hr><strong>Errors:</strong><ul>';
        results.errors.forEach(err => {
            html += `<li class="text-danger">${escapeHtml(err)}</li>`;
        });
        html += '</ul>';
    }

    Swal.fire({
        title: 'Upload Results',
        html: html,
        icon: results.failed > 0 ? 'warning' : 'success',
        confirmButtonText: 'OK'
    });
}

function displayErrorModal(errorLog) {
    document.getElementById('errorModalBody').innerHTML = `<pre class="text-danger">${escapeHtml(errorLog)}</pre>`;
    new bootstrap.Modal(document.getElementById('errorModal')).show();
}

function downloadErrorLog() {
    const content = document.getElementById('errorModalBody').innerText;
    const blob = new Blob([content], { type: 'text/plain' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `error_log_${Date.now()}.txt`;
    link.click();
    URL.revokeObjectURL(link.href);
}

function viewUploadDetails(uploadId) {
    // Implement view details functionality
    window.location.href = `/${uploadId}`;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Load initial history on page load
document.addEventListener('DOMContentLoaded', () => {
    loadUploadHistory();
});
</script>
@endsection
