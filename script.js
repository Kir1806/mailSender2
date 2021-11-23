"use strict"

const form = document.getElementById('form');

const formImage = document.getElementById('formImage');
const formPreview = document.getElementById('formPreview');

console.log(document.readyState);
// document.addEventListener('DOMContetntLoaded', function () {
    
    
    
    form.addEventListener('submit', formSend);

    async function formSend(event) {
        event.preventDefault();

        let error = formValidate(form);
        let formData = new FormData(form);
        formData.append('image', formImage.files[0]);

        if (error === 0) {
            form.classList.add('_sending');
            let response = await fetch('sendmail.php', {
                method: 'POST',
                body: formData
            });
            if(response.ok){
                let result = await response.json();
                alert(result.message);
                formPreview.innerHTML = '';
                form.reset();
                form.classList.remove('_sending');
            } else {
                alert('error');
                form.classList.remove('_sending');

            }
        } else {
            alert('Заполните обязательные поля');
        }


    }


    function formValidate(form) {
        let error = 0;
        let formReq = document.querySelectorAll('._req');
        

        for (let i = 0; i < formReq.length; i++) {
            const input = formReq[i];
            formRemoveError(input);

            if(input.classList.contains('_email')) {
                if (emailTest(input)) {
                    formAddError(input);
                    error++;
                }
            } else if (input.getAttribute('type') === 'checkbox'  && input.checked === false) {
                formAddError(input);
                error++;
            } else {
                if (input.value === '') {
                    formAddError(input);
                    error++;
                }
            }            
        } 
        return error;       
    }

    function formAddError(input) {
        input.parentElement.classList.add('_error');
        input.classList.add('_error');
    }

    function formRemoveError(input) {
        input.parentElement.classList.remove('_error');
        input.classList.remove('_error');
    }

    function emailTest(input) {
        return !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,8})+$/.test(input.value);
    }


    // ----------  картинка ------------

    function uploadFile(file) {

        // проверка типа файла
        if (!['image/jpg', 'image/png', 'image/gif'].includes(file.type)) {
            alert('Разрешены только изображения');
            formImage.value = '';
            return;
        }
        // поверка размера файла
        if (file.size > 2 * 1024 * 1024) {
            alert('Файл должен быть менее 2 Мб');
            return;
        }

        var reader = new FileReader();
        reader.onload = function (event) {
            formPreview.innerHTML = `<img src="${event.target.result}" alt="Фото">`;
        };

        reader.onerror = function(event) {
            alert('error');
        };
        reader.readAsDataURL(file);
    }


    formImage.addEventListener('change', () => {
        uploadFile(formImage.files[0]);
    });

// });

