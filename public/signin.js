$(document).ready(function () {

    // покажем форму
    $('#signinForm').fadeIn(1200);

    // обработка сабмита формы регистрации
    $('#signinForm').submit(function (e) {

        // не дадим случится стандартному сабмиту
        e.preventDefault();

        $('#errorMessage').fadeOut();

        $.ajax({
            url: './register.php',
            type: 'POST',
            async: true,
            data: {
                name: $('#inputName').val(),
                surname: $('#inputSurname').val(),
                email: $('#inputEmail').val(),
                password: $('#inputPassword').val(),
                passwordConfirm: $('#inputPasswordConfirm').val()
            },
            success: function (data, textStatus, jqXHR) {
                let errorMessage;
                // "ОК" значит регистрация свершилась успешна! иначе считаем как ошибку
                if (data != 'OK') {
                    try {
                        errorMessage = JSON.parse(data);
                        errorMessage = errorMessage.join('<br>');
                    } catch (e) {
                        errorMessage = data;
                    }
                    $('#errorMessage').html(errorMessage).fadeIn();
                } else {
                    $('#signinForm').hide();
                    $('#successMessage').fadeIn(1200);
                }
            },
            error: function () {
                $('#errorMessage').html('Произошла ошикбка, Что то пошло не так');
            },
        });

    });

    // Обработка кнопки "Еще!"
    $('#registerAgain').click(function () {

        $('#successMessage').fadeOut(1500, function () {
            $('#loader').fadeIn();
        });

        setTimeout(function () {
            location.reload();
        }, 3000);
    })
});