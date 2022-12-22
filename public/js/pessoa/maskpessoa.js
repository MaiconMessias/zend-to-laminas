$(document).ready(function(){
    //console.log("arquivo maskcontato");
    $("#rg").inputmask("99.999.999-9");
    
    if ($( "#tipo" ).val() == 1) {
        $("#cpfcnpj").inputmask("999.999.999-99");
    } else  {
        $("#cpfcnpj").inputmask("99.999.999/9999-99");
    }
    
    $("#dtnasc").inputmask("99/99/9999");
    $("#tipo").change(function(){
        if ($( "#tipo" ).val() == 1) {
            $("#cpfcnpj").inputmask("999.999.999-99");
            $("#cpfcnpj").attr ('placeholder', '999.999.999-99');
        } else {
            $("#cpfcnpj").inputmask("99.999.999/9999-99");
            $("#cpfcnpj").attr ('placeholder', '99.999.999/9999-99');
        }
    });
});
