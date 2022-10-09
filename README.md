# EK Starter


## Description
Starter de plugin permettant d'appréhender:  
- création d'une nav spécifique à un plugin en back
- configuration de plugin
- envois/réception AJAX vers un serveur local
- envois POST vers un serveur distant
- création d'une catégorie de blocs Gutenberg
- création d'un bloc Gutenberg simple
- différenciation des scripts et styles cotés back et front
- update à distance du plugin


## Back office
### Configuration
La page de configuration du plugin est disponible à l'adresse :  
/wp-admin/admin.php?page=ekstrtr-settings\
Deux paramètres sont configurés :
- url du serveur distant auquel les appels sont envoyés
- token à passer dans les headers de la demande

### Test
Une page de test du des appels externes est disponible ici :  
/wp-admin/admin.php?page=ekstrtr-test\
Le testeur du back-office permet d'accéder à la réponse du serveur

### Edition de page
Le plugin dispose d'un block Gutenberg simple, permettant d'ajouter 
la fenêtre d'envoi à n'importe quelle page. /
Le bloc s'appelle **Fenêtre Debugger**, il est dans 
le groupe **EK STARTER**./
Il permet de donner un titre à la fenêtre et d'activer le mode 
debug. Ce mode permet de décider si la réponse du serveur 
doit être affichée.

## Envoi de requêtes externes
La fonctionnalité principale du plugin est l'envoi de requête à un serveur 
distant, contenant :
- un mot (word)
- un nombre (ID)

L'envoi suit ces étapes :
1. une requête AJAX au serveur local par la fonction JS **ekstater_envoyer()** 
2. réception par wordpress par **ekstater_requeteIn()** et préparation d'envoi 
3. requête au serveur distant par **ekstater_requeteOut()** et traitement de la réponse 
4. retour au client et traitmeent par JS **ekstater_traiter_retour()** 

En front, selon si le mode debug est activé ou non, le fichier JS contenant les fonctions 
n'est pas le même, ce qui explique qu'elles soient en double dans le code source.  
Dans un cas, on affichera la réponse du serveur distant, dans l'autre, il sera juste 
loggé dans la console JS.


## User Data / User Meta
Si le visiteur est connecté, le mode debug en front permettra d'afficher 
toutes les variables associées à son compte.
- Les userdata (**get_userdata()**) présentent les informations typique d'un compte WP 
- Les usermeta (**get_user_meta()**) sont les données généralement ajoutée par un plugin 
thème, typiquement les données venant d'active directory/SSO... si le compte est issue d'une 
source externe.

Si une variable s'avère utile, plutôt que charger l'intégralité des meta, on préferera 
demander uniquement cette variable : *get_user_meta(user_id, meta, true);*  
Le 3e paramètre à true permet de récupérer la valeur unbiquement, et non un tableau.


### Notes
- Les CSS sont générées à partir de fichier LESS
- Toutes les fenêtre de debug (front et back) sont issue d'une même 
fonction __ekstater_debugHTML()__, d'un manière générale, on préferera 
avoir des fonctions spécifiques au front et au back distinctes. Cela permet 
cependant d'offrir un exemple d'utilisation du paramètre **render_callback** 
de Gutenberg, qui est très utile pour la création de blocks dynamiques.


