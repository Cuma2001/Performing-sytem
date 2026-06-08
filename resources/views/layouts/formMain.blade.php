
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('layouts.head')
    </head>
    <style>
        /* loader */


.loader-wrapper{
  width:100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  background-color: rgba(0, 0, 0, 0.3);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.ring{
  width: 200px;
  height: 200px;
  border: 0px solid #18345d;
  border-radius: 50%;
  position: absolute;
}

.ring:nth-child(1){
  border-bottom-width: 8px;
  border-color: #18345d;
  animation: rotate1 2s linear infinite;
}
.ring:nth-child(2){
  border-right-width: 8px;
  border-color: #C3bE5C;
  animation: rotate2 2s linear infinite;
}
.ring:nth-child(3){
  border-top-width: 8px;
  border-color: #fff;
  animation: rotate3 2s linear infinite;
}

.loading{
  color: white;
}

@keyframes rotate1 {
  0% {  transform: rotateX(35deg) rotateY(-45deg) rotateZ(0deg);  }

  100% { transform: rotateX(35deg) rotateY(-45deg) rotateZ(360deg); }
}
@keyframes rotate2 {
  0% {  transform: rotateX(50deg) rotateY(10deg) rotateZ(0deg);  }

  100% { transform: rotateX(50deg) rotateY(10deg) rotateZ(360deg); }
}
@keyframes rotate3 {
  0% {  transform: rotateX(35deg) rotateY(55deg) rotateZ(0deg);  }

  100% { transform: rotateX(35deg) rotateY(55deg) rotateZ(360deg); }
}



    </style>
<body>
    <div class="loader-wrapper">
    <div class="ring"></div>
    <div class="ring"></div>
    <div class="ring"></div>
    <span class="loading">Loading...</span>

</div>
        @yield('content')
</body>
<script>
    $(window).on("load",function(){
      $(".loader-wrapper").fadeOut("slow");
    });
</script>
</html>
