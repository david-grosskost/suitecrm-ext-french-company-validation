# Contrôles des entreprises françaises (`french-company-validation`)

Extension SuiteCRM qui vérifie le **SIRET** et le **code NAF** des comptes, ajoutés par
l'extension [french-company-fields](https://github.com/david-grosskost/suitecrm-ext-french-company-fields)
(à activer avec elle : sans ses champs, celle-ci ne fait rien).

| Contrôle | Refusé | Message |
|---|---|---|
| Format du SIRET | pas 14 chiffres | « Le SIRET doit compter 14 chiffres. » |
| Clé du SIRET | clé de Luhn fausse (règle propre à La Poste pour ses établissements) | « … sa clé de contrôle est fausse (faute de frappe ?). » |
| Format du code NAF | autre chose que 2 chiffres, un point, 2 chiffres et une lettre | « … (ex. 62.01Z). » |

Un même SIRET sur deux comptes n'est pas refusé pour l'instant (évolution possible).

Champs vides acceptés : ni le SIRET ni le code NAF ne sont obligatoires. La saisie est nettoyée
à l'enregistrement : espaces, points et tirets retirés du SIRET (« 732 829 320 00074 »), code
NAF en majuscules avec son point (« 6201z » → « 62.01Z »).

## Comment c'est fait

- **Sur la fiche, pendant la saisie** (`backend/Process/FieldCheckHandler.php`) : les deux champs
  déclarent un contrôle asynchrone de SuiteCRM 8 (`asyncValidators`, dans
  `legacy/Extension/modules/Accounts/Ext/Vardefs/`) ; SuiteCRM 8 appelle le processus
  `french-company-validation-check`, qui renvoie le message affiché sous le champ. Tant qu'il est là,
  la fiche ne s'enregistre pas.
- **À chaque enregistrement, quelle que soit la voie** (import, API, workflow) : un logic hook
  `before_save` des comptes (`legacy/include/french-company-validation/AccountCheck.php`) nettoie la
  saisie, applique les mêmes règles et refuse l'enregistrement s'il en manque une.
- Les règles elles-mêmes (`Siret`, `NafCode`) sont des fonctions pures, testées sans SuiteCRM.

## Développer

```bash
make extension@validate   # format : manifeste, composer.json, place des fichiers
make quality@lint         # style du code, scripts
make quality@stan         # analyse statique (PHPStan, niveau max)
make test@unit            # tests unitaires (PHPUnit)
```

Les commandes, la CI et les règles viennent de
[suitecrm-devkit](https://github.com/david-grosskost/suitecrm-devkit).
