# Utilisation

## Ce qui change

Sur la fiche d'un compte, un SIRET ou un code NAF faux affiche un message sous le champ, et la
fiche ne s'enregistre pas tant qu'il n'est pas corrigé :

| Contrôle | Refusé | Message |
|---|---|---|
| Format du SIRET | pas 14 chiffres | « Le SIRET doit compter 14 chiffres. » |
| Clé du SIRET | clé de contrôle fausse | « Ce SIRET n'existe pas : sa clé de contrôle est fausse (faute de frappe ?). » |
| Format du code NAF | autre chose que 2 chiffres, un point, 2 chiffres et une lettre | « Le code NAF s'écrit 2 chiffres, un point, 2 chiffres et une lettre (ex. 62.01Z). » |

Les mêmes règles s'appliquent à tout enregistrement d'un compte, quelle que soit la voie :
import, API, workflow. Un compte refusé n'est pas enregistré.

**La saisie est nettoyée** à l'enregistrement :

- SIRET : espaces, points et tirets retirés (« 732 829 320 00074 » → « 73282932000074 ») ;
- code NAF : en majuscules, avec son point (« 6201z » → « 62.01Z »).

Les champs vides sont acceptés : ni le SIRET ni le code NAF ne sont obligatoires.

## Réglages

Aucun.

## Données de démo

Aucune : les SIRET des données de démo (french-company-fields) ont déjà une clé valide.

## Limites

- Un même SIRET sur deux comptes n'est pas refusé (évolution possible).
- Le contrôle vérifie que le SIRET est **possible**, pas qu'il **existe** : aucune
  interrogation de l'annuaire INSEE.
