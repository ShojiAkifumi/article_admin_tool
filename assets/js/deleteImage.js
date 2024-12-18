const image1 = document.getElementById('image1');
const image2 = document.getElementById('image2');
const fileImage1 = document.getElementById('fileImage1');
const fileImage2 = document.getElementById('fileImage2');
const imgDeleteBtn1 = document.getElementById('img-delete-btn-1');
const imgDeleteBtn2 = document.getElementById('img-delete-btn-2');

imgDeleteBtn1.addEventListener('click', function(e){
    e.preventDefault();
    image1.value = "";
    image1.classList.remove("valid");
    fileImage1.value = "";
    imgDeleteBtn1.style.display = "none";
});
imgDeleteBtn2.addEventListener('click', function(e){
    e.preventDefault();
    image2.value = "";
    image2.classList.remove("valid");
    fileImage2.value = "";
    imgDeleteBtn2.style.display = "none";
});

document.addEventListener('DOMContentLoaded', function() {
    if(image1.value === ""){
        imgDeleteBtn1.style.display = "none";
    } else {
        imgDeleteBtn1.style.display = "block";
    }
    if(image2.value === ""){
        imgDeleteBtn2.style.display = "none";
    } else {
        imgDeleteBtn2.style.display = "block";
    }
});

image1.addEventListener('change', function(){
    console.log(imgDeleteBtn1.style.display);
    if(image1.value === ""){
        imgDeleteBtn1.style.display = "none";
    } else {
        imgDeleteBtn1.style.display = "block";
    }
});

image2.addEventListener('change', function(){
    if(image2.value === ""){
        imgDeleteBtn2.style.display = "none";
    } else {
        imgDeleteBtn2.style.display = "block";
    }
});