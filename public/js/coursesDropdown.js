let dropDown = document.getElementById('courses_dropdown');
let dropDownBtn = document.getElementById('courses_dropdown_btn');
dropDownBtn.addEventListener('click', function () {
    if (dropDown.style.display === 'block'){
        dropDown.style.display = 'none';
    } else {
        dropDown.style.display = 'block';
    }
})
