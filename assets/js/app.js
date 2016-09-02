//jQuery Validate plugin validation

    $(document).ready(function() {
        $('#register-form').validate({
            rules: {
                first_name: "required",
                last_name: "required",
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    pattern: /^[A-Za-z0-9\w]{4,20}/
                },
                rep_pass: {
                    equalTo: 'input[name="password"]'
                }
            },
            messages: {
                first_name: "This field is required",
                last_name: "This field is required",
                email: {
                  required: "This field is required",
                  email: "Invalid email format! Must have a name@domain.com format"
                },
                password: {
                   required: "This field is required",
                   pattern: "Invalid password format!"
                },
                rep_pass: {
                   required: "This field is required",
                   equalTo: "Passwords must match!"
                }
            }
            ,
            errorPlacement: function(error, element) {
                  element.closest('div').after(error);
            }
        });
    });
