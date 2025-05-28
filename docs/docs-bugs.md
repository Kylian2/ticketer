# Encyclopédie des bugs rencontrés

## Feuilles de style chargées mais non reconnues

**Contexte** : les feuilles de style étaient trouvées par le navigateur mais pas interprétées comme telles. Le header reçu `Content-Type: plain/text` les définissait comme de simples fichiers texte. Le header attendu pour les feuilles de style est `Content-Type: text/css`.

Pour savoir quelles en-têtes attribuer, Nginx se base sur l'extension du fichier et les fixe automatiquement grâce au contenu du fichier `mime.types`.

**Solution** : Il faut vérifier que le fichier `mime.types` est bien inclus. S'il ne l'est pas, il faut l'inclure avec la directive `include mime.types;` au début du fichier de configuration Nginx.

Exemple :

```
http {
    include       mime.types;
    default_type  application/octet-stream;

    server {
        ...
    }
}
```