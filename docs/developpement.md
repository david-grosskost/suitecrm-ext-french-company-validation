# Développement

Les commandes `make`, la CI et le format des extensions viennent de
[suitecrm-devkit](https://github.com/david-grosskost/suitecrm-devkit#documentation), à la
version de `composer.json`. Cette page ne décrit que ce qui est propre à l'extension.

## Ajouter un contrôle

1. Écrire la règle dans une classe de `legacy/include/french-company-validation/`, avec son
   nettoyage et la clé du message d'erreur, et son test.
2. Ajouter le message dans les deux fichiers de langue (`legacy/Extension/application/Ext/Language/`).
3. Brancher la règle des deux côtés : `AccountCheck` (à l'enregistrement) et
   `backend/FieldDefinitions/FieldChecks` + `FieldCheckHandler` (sur la fiche).
4. Compléter le tableau de [Utilisation](utilisation.md).

## Vérifier

```bash
make extension@validate && make quality@lint && make quality@stan && make test@unit
make package@build && make package@scan
```

## Tester sur une instance locale

1. Avec french-company-fields installée : `make package@build`, puis sur l'instance locale de
   suitecrm-core, **Administration → Chargeur de module** : **Choisir un fichier**
   (`build/french-company-validation-<version>.zip`), **Télécharger**, **Installer**.
2. Sur la fiche d'un compte : un SIRET de 13 chiffres, puis un SIRET à la clé fausse
   (`73282932000075`), puis un code NAF `6201`, chacun doit afficher son message ; `6201z` doit
   devenir `62.01Z` à l'enregistrement.
3. Dans suitecrm-core : `make test@regression`.

## Publier une version

Pull request relue, puis merge (squash) : release-please ouvre la pull request
« chore(main): release x.y.z ». La merger crée le tag, la release et son paquet.
suitecrm-core la récupère avec
`make composer@update PACKAGES="david-grosskost/suitecrm-ext-french-company-validation"`.

Mettre à jour `README.md` et `docs/` dans la même pull request que le changement qu'ils
décrivent.
