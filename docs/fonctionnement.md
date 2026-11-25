# Fonctionnement

Deux contrôles, avec les mêmes règles : l'un sur la fiche de SuiteCRM 8 pendant la saisie,
l'autre à chaque enregistrement, pour toutes les autres voies.

## Fichiers

| Fichier | Rôle |
|---|---|
| `legacy/include/french-company-validation/Siret.php` | la règle du SIRET : nettoyage, 14 chiffres, clé de contrôle |
| `legacy/include/french-company-validation/NafCode.php` | la règle du code NAF : nettoyage, format |
| `legacy/include/french-company-validation/AccountCheck.php` | le hook `before_save` des comptes : nettoie, contrôle, refuse |
| `legacy/Extension/modules/Accounts/Ext/LogicHooks/` | déclare le hook |
| `legacy/Extension/application/Ext/Language/` | les messages (français, anglais) |
| `backend/FieldDefinitions/AddFieldChecks.php`, `FieldChecks.php` | ajoutent le contrôle asynchrone aux deux champs, dans les définitions que SuiteCRM 8 envoie à la fiche |
| `backend/Process/FieldCheckHandler.php` | le processus `french-company-validation-check`, appelé par la fiche |
| `tests/` | tests des règles, des contrôles de champ et des déclarations |

## Les règles

- **SIRET** : 14 chiffres, le SIREN de l'entreprise (9) puis le NIC de l'établissement (5),
  avec une clé de Luhn. Exception : les établissements de La Poste (SIREN `356000000`) suivent
  leur propre règle (somme des chiffres).
- **Code NAF** (APE) : 2 chiffres, un point, 2 chiffres et une lettre.

Ce sont des fonctions pures (`Siret`, `NafCode`), testées sans SuiteCRM.

## Sur la fiche, pendant la saisie

À la construction des définitions des champs, SuiteCRM 8 reçoit pour le SIRET et le code NAF
un **contrôle asynchrone** (`asyncValidators`). La fiche appelle alors le processus
`french-company-validation-check`, qui renvoie le message à afficher sous le champ ; tant qu'il
est là, la fiche ne s'enregistre pas.

## À chaque enregistrement

Le hook `before_save` des comptes nettoie la saisie, applique les mêmes règles et refuse
l'enregistrement si l'une échoue. C'est la dernière ligne, pour toutes les voies que la fiche
ne couvre pas (import, API, workflow).

## Données en base

Aucune donnée propre : l'extension nettoie et contrôle les colonnes `fr_siret` et
`fr_naf_code` de french-company-fields.

## Installation et désinstallation

Pas de script : le Chargeur de module copie les fichiers et lance la réparation rapide.

## Points d'attention

- Le scanner du Chargeur de module refuse `array_map` et `array_filter` : les règles utilisent
  des boucles.
- SuiteCRM ne charge que le fichier du hook pour l'exécuter : `AccountCheck.php` charge
  lui-même les règles.
