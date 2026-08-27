{{-- resources/views/utilities/master-upload.blade.php --}}
@extends('layouts.app')
<style>
    .upload-card:hover {
        background-color: #f9f9f9;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .card-body {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
</style>
@section('content')
    <div style="container-fluid px-4">
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-header">
                <h4><i class="fas fa-upload"></i> KPI Upload Utility</h4>
            </div>

            <div class="card-body">
                <div class="upload-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                    <!-- Store KPI Upload -->
                    <div class="upload-card" style="border: 2px dashed #e2e8f0; border-radius: 16px; padding: 20px; text-align: center; transition: all 0.3s;">
                        <i class="fas fa-store" style="font-size: 48px; color: #2c3e50; margin-bottom: 12px;"></i>
                        <h4 style="color: #1e2f3f; margin-bottom: 8px;">Store KPI Targets</h4>
                        <p style="margin-bottom: 12px; color: #666;">Upload Excel/CSV with store-level KPI targets</p>
                        <form id="storeKPIForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" id="storeFile" accept=".xlsx,.csv,.xls" style="display: none;">
                            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                <button type="button" class="btn btn-secondary" onclick="document.getElementById('storeFile').click()">
                                    <i class="fas fa-folder-open"></i> Choose File
                                </button>
                                <button type="submit" class="btn btn-primary" style="background: #e74c3c;">
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                            </div>
                        </form>
                        <div id="storeUploadStatus" style="margin-top: 10px;"></div>
                    </div>

                    <!-- Sales Agent KPI Upload -->
                    <div class="upload-card" style="border: 2px dashed #e2e8f0; border-radius: 16px; padding: 20px; text-align: center; transition: all 0.3s;">
                        <i class="fas fa-user" style="font-size: 48px; color: #2c3e50; margin-bottom: 12px;"></i>
                        <h4 style="color: #1e2f3f; margin-bottom: 8px;">Sales Agent KPI Targets</h4>
                        <p style="margin-bottom: 12px; color: #666;">Upload targets for individual sales agents</p>
                        <form id="salesAgentKPIForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" id="salesAgentFile" accept=".xlsx,.csv,.xls" style="display: none;">
                            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                <button type="button" class="btn btn-secondary" onclick="document.getElementById('salesAgentFile').click()">
                                    <i class="fas fa-folder-open"></i> Choose File
                                </button>
                                <button type="submit" class="btn btn-primary" style="background: #e74c3c;">
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                            </div>
                        </form>
                        <div id="salesAgentUploadStatus" style="margin-top: 10px;"></div>
                    </div>

                    <!-- Supervisor KPI Upload -->
                    <div class="upload-card" style="border: 2px dashed #e2e8f0; border-radius: 16px; padding: 20px; text-align: center; transition: all 0.3s;">
                        <i class="fas fa-user-tie" style="font-size: 48px; color: #2c3e50; margin-bottom: 12px;"></i>
                        <h4 style="color: #1e2f3f; margin-bottom: 8px;">Supervisor KPI Targets</h4>
                        <p style="margin-bottom: 12px; color: #666;">Upload Excel/CSV with supervisor-level KPIs</p>
                        <form id="supervisorKPIForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" id="supervisorFile" accept=".xlsx,.csv,.xls" style="display: none;">
                            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                <button type="button" class="btn btn-secondary" onclick="document.getElementById('supervisorFile').click()">
                                    <i class="fas fa-folder-open"></i> Choose File
                                </button>
                                <button type="submit" class="btn btn-primary" style="background: #e74c3c;">
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                            </div>
                        </form>
                        <div id="supervisorUploadStatus" style="margin-top: 10px;"></div>
                    </div>

                    <!-- Company KPI Upload -->
                    <div class="upload-card" style="border: 2px dashed #e2e8f0; border-radius: 16px; padding: 20px; text-align: center; transition: all 0.3s;">
                        <i class="fas fa-building" style="font-size: 48px; color: #2c3e50; margin-bottom: 12px;"></i>
                        <h4 style="color: #1e2f3f; margin-bottom: 8px;">Company KPIs</h4>
                        <p style="margin-bottom: 12px; color: #666;">Upload company-wide KPI targets</p>
                        <form id="companyKPIForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" id="companyFile" accept=".xlsx,.csv,.xls" style="display: none;">
                            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                <button type="button" class="btn btn-secondary" onclick="document.getElementById('companyFile').click()">
                                    <i class="fas fa-folder-open"></i> Choose File
                                </button>
                                <button type="submit" class="btn btn-primary" style="background: #e74c3c;">
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                            </div>
                        </form>
                        <div id="companyUploadStatus" style="margin-top: 10px;"></div>
                    </div>

                    <!-- MTN KPI Upload -->
                    <div class="upload-card" style="border: 2px dashed #e2e8f0; border-radius: 16px; padding: 20px; text-align: center; transition: all 0.3s;">
                        <i class="fas fa-chart-line" style="font-size: 48px; color: #2c3e50; margin-bottom: 12px;"></i>
                        <h4 style="color: #1e2f3f; margin-bottom: 8px;">MTN Benchmark KPIs</h4>
                        <p style="margin-bottom: 12px; color: #666;">Upload MTN KPI targets for comparison</p>
                        <form id="mtnKPIForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" id="mtnFile" accept=".xlsx,.csv,.xls" style="display: none;">
                            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                <button type="button" class="btn btn-secondary" onclick="document.getElementById('mtnFile').click()">
                                    <i class="fas fa-folder-open"></i> Choose File
                                </button>
                                <button type="submit" class="btn btn-primary" style="background: #e74c3c;">
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                            </div>
                        </form>
                        <div id="mtnUploadStatus" style="margin-top: 10px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Section -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-history"></i> Recent Upload History</h4>
            </div>
            <div class="card-body">
                <div style="overflow-x: auto;">
                    <table class="table table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>File Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Records</th>
                            </tr>
                        </thead>
                        <tbody id="uploadHistoryTable">
                            <tr>
                                <td colspan="5" style="text-align: center;">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function setupUpload(formId, fileInputId, uploadType, statusDivId) {
            $('#' + formId).on('submit', function (e) {
                e.preventDefault();
                
                let fileInput = document.getElementById(fileInputId);
                if (!fileInput.files || fileInput.files.length === 0) {
                    $('#' + statusDivId).html('<p style="color: #e74c3c;">Please select a file first.</p>');
                    return;
                }
                
                let formData = new FormData(this);
                formData.append('upload_type', uploadType);

                $('#' + statusDivId).html(`
                    <div style="margin-top: 10px;">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span style="color: #2c3e50; margin-left: 8px;">Uploading and processing...</span>
                    </div>
                `);

                let submitBtn = $('#' + formId + ' button[type="submit"]');
                submitBtn.prop('disabled', true);

                $.ajax({
                    url: '{{ route('utility.master-process') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    success: function (response) {
                        let statusHtml = `
                            <div style="margin-top: 10px; padding: 10px; background: #d4edda; border-radius: 6px; border-left: 4px solid #28a745;">
                                <p style="color: #155724; margin: 0;">
                                    <i class="fas fa-check-circle" style="color: #28a745;"></i>
                                    ✓ Upload successful! 
                                    ${response.records || 0} records processed.
                                    ${response.success_records !== undefined ? `<br><small>Success: ${response.success_records} | Failed: ${response.failed_records}</small>` : ''}
                                </p>
                            </div>
                        `;
                        $('#' + statusDivId).html(statusHtml);
                        
                        document.getElementById(fileInputId).value = '';
                        loadUploadHistory();
                        setTimeout(() => location.reload(), 3000);
                    },
                    error: function (xhr) {
                        let errorMsg = 'Unknown error occurred';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 422) {
                            errorMsg = 'Invalid file format. Please check the file and try again.';
                        } else if (xhr.status === 409) {
                            errorMsg = 'This file has already been uploaded.';
                        }
                        
                        let statusHtml = `
                            <div style="margin-top: 10px; padding: 10px; background: #f8d7da; border-radius: 6px; border-left: 4px solid #dc3545;">
                                <p style="color: #721c24; margin: 0;">
                                    <i class="fas fa-exclamation-circle" style="color: #dc3545;"></i>
                                    ✗ Upload failed: ${errorMsg}
                                </p>
                            </div>
                        `;
                        $('#' + statusDivId).html(statusHtml);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                    }
                });
            });
        }

        setupUpload('storeKPIForm', 'storeFile', 'store', 'storeUploadStatus');
        setupUpload('supervisorKPIForm', 'supervisorFile', 'supervisor', 'supervisorUploadStatus');
        setupUpload('companyKPIForm', 'companyFile', 'company', 'companyUploadStatus');
        setupUpload('mtnKPIForm', 'mtnFile', 'mtn', 'mtnUploadStatus');
        setupUpload('salesAgentKPIForm', 'salesAgentFile', 'sales_agent', 'salesAgentUploadStatus');

        function loadUploadHistory() {
            $.get('{{ route('utility.master-history') }}', function (data) {
                let html = '';
                if (data && data.length > 0) {
                    data.forEach(upload => {
                        let statusClass = upload.status_badge || 'secondary';
                        let statusColor = {
                            'success': 'green',
                            'danger': '#dc3545',
                            'warning': '#ffc107',
                            'info': '#17a2b8',
                            'secondary': '#6c757d'
                        }[statusClass] || '#6c757d';
                        
                        let recordsDisplay = upload.records || 0;
                        if (upload.success_records !== undefined && upload.failed_records !== undefined) {
                            recordsDisplay = `${upload.success_records} ✓ / ${upload.failed_records} ✗`;
                        }
                        
                        html += '<tr>' +
                            '<td>' + (upload.created_at || '') + '</td>' +
                            '<td>' + (upload.file_name || '') + '</td>' +
                            '<td><span style="background: #f39c12; color: #1e2f3f; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 12px; text-transform: uppercase;">' + (upload.type || '') + '</span></td>' +
                            '<td><span style="color: ' + statusColor + '; font-weight: 500;">' + (upload.status || '') + '</span></td>' +
                            '<td>' + recordsDisplay + '</td>' +
                            '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="5" style="text-align: center; padding: 20px; color: #6c757d;">No uploads yet</td></tr>';
                }
                $('#uploadHistoryTable').html(html);
            }).fail(function() {
                $('#uploadHistoryTable').html('<tr><td colspan="5" style="text-align: center; color: #dc3545;">Failed to load history</td></tr>');
            });
        }

        $(document).ready(function() {
            loadUploadHistory();
            setInterval(loadUploadHistory, 30000);
        });

        // File input change handlers
        const fileInputConfigs = [
            { inputId: 'storeFile', statusId: 'storeUploadStatus', label: 'Store KPI' },
            { inputId: 'supervisorFile', statusId: 'supervisorUploadStatus', label: 'Supervisor KPI' },
            { inputId: 'companyFile', statusId: 'companyUploadStatus', label: 'Company KPI' },
            { inputId: 'mtnFile', statusId: 'mtnUploadStatus', label: 'MTN KPI' },
            { inputId: 'salesAgentFile', statusId: 'salesAgentUploadStatus', label: 'Sales Agent KPI' }
        ];

        fileInputConfigs.forEach(config => {
            document.getElementById(config.inputId)?.addEventListener('change', function (e) {
                let statusDiv = document.getElementById(config.statusId);
                if (this.files && this.files[0]) {
                    let file = this.files[0];
                    let fileSize = (file.size / 1024 / 1024).toFixed(2);
                    let statusHtml = `
                        <div style="margin-top: 8px; padding: 6px 10px; background: #e8f4f8; border-radius: 4px; border-left: 3px solid #2c3e50;">
                            <span style="color: #2c3e50; font-size: 14px;">
                                <i class="fas fa-file"></i> 
                                ${file.name} (${fileSize} MB)
                            </span>
                        </div>
                    `;
                    statusDiv.innerHTML = statusHtml;
                } else {
                    statusDiv.innerHTML = '';
                }
            });
        });
    </script>
@endsection