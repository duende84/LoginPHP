$(document).ready(function (){
        
    $("#btnEnviar").click(function() {
        var user = $("#txtNombreUsuario").val();
        var hash = $("#hash").val();                
        if (user == "" || hash == "" ) {            
            $("#hash").val("");
            $("#password").val("");
            alert("Ingrese su nombre de usuario y contraseña")
        }else{
            $.post('Controlador/Fachada.php', {        
                clase : 'Login',
                oper  : 'login',
                username  : user,
                hash : hash
            }, function(data) {
                if (data == "exito") {
                    window.location = "Admin.php";
                }
            }, 'json');
        }        
    });
});