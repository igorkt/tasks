<?php
    $this->registerCssFile("@web/css/swagger/swagger-ui.css");
    $this->registerJsFile("@web/js/swagger/swagger-ui-bundle.js");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Swagger UI</title>
</head>
<body>
<div id="swagger-ui"></div>
<script>
    window.onload = function() {
        SwaggerUIBundle({
            url: "/swagger/index",
            dom_id: '#swagger-ui'
        });
    };
</script>
</body>
</html>