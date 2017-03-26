RewriteEngine On

# Si le fichier auquel on tente d'accéder existe (si on veut accéder à une image par exemple).
# Alors on ne réécrit pas l'URL.
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ bootstrap.php?app=Frontend [QSA,L]