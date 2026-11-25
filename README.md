# Contrôles des entreprises françaises (`french-company-validation`)

Vérifie le **SIRET** et le **code NAF** des comptes, sur la fiche pendant la saisie comme à
chaque enregistrement (import, API, workflow) : un SIRET doit compter 14 chiffres avec une clé
de contrôle juste, un code NAF s'écrire comme `62.01Z`. La saisie est nettoyée au passage
(espaces, points, majuscules).

| Fiche | |
|---|---|
| Version de SuiteCRM | 8.10 (`>=8.10 <9`) |
| Types | `logic-hook`, `fields`, `lang` |
| Dépend de | [french-company-fields](https://github.com/david-grosskost/suitecrm-ext-french-company-fields) : sans ses champs, celle-ci ne fait rien |
| Installée d'office | non |

## Installer

Installer d'abord french-company-fields, puis celle-ci, par **Administration → Chargeur de
module** : l'extension y figure dans la liste des paquets disponibles, cliquer sur
**Installer**. Depuis le terminal, dans suitecrm-core :

```bash
make extensions@install SLUGS="french-company-fields french-company-validation"
```

La désinstallation se fait au même endroit.

## Documentation

| Page | Contenu |
|---|---|
| [Utilisation](docs/utilisation.md) | ce qui est refusé, les messages, le nettoyage de la saisie |
| [Fonctionnement](docs/fonctionnement.md) | contrôle sur la fiche (SuiteCRM 8), contrôle à l'enregistrement (hook), les règles |
| [Développement](docs/developpement.md) | ajouter un contrôle, vérifier, tester en local, publier |

Les commandes, la CI et le format des extensions sont décrits dans la documentation de
[suitecrm-devkit](https://github.com/david-grosskost/suitecrm-devkit#documentation).
