# strails

Social trail state

Strails è un'applicazione web utile agli appassionati di Mountain bike e trekking.

L'applicazione Strails ha un funzionamento molto semplice.

Il suo scopo è quello di poter descrivere lo stato di un sentiero o di un segmento Strava.

Gli utenti di Strails possono conoscere quasi in tempo reale le condizioni di un tragitto e decidere quali sentieri percorrere, preventivamente consapevoli e informati su eventuali pericoli presenti nel tracciato scelto. Queste informazioni, condivise in modo social su Strails, permettono quindi di migliorare la sicurezza degli utenti e di poter scegliere il percorso più divertente e nelle migliori condizioni.

Inoltre anche i trail builder potranno intervenire in modo più preciso e puntuale per il ripristino dei sentieri grazie alle segnalazioni di Strails. Strails necessità di una banale registrazione con email e password oppure del login con il proprio account Google, Facebook o Strava. La registrazione consente di commentare i soli sentieri presenti su openstreetmap.

Cliccando anche su "Connect with Strava" si può accedere ai propri dati ed a tutti i segmenti Strava. La connessione con Strava permette di poter recuperare le proprie attività e visualizzarle su una mappa (in arancione), contestualmente appaiono anche i sentieri OSM (in blu) ed i segmenti Strava (in rosso). Gli ultimi due potranno quindi essere più facilmente individuabili per essere "commentati" cliccandoci sopra.

Per individuare un sentiero e poterlo "commentare", è anche possibile scorrere semplicemente la mappa cliccando su "apri e scorri la mappa". Zommando fino alla zona di interesse, oppure cliccando sull'icona in alto a sinistra per zommare sulla posizione corrente, si possono visualizzare tutti i sentieri cliccabili. Ogni utente può anche inserire un sentiero o un segmento nei propri preferiti per poterlo consultare e commentare più agevolmente. Tutto ciò che è nei preferiti può avere colore celeste (se non ha mai avuto commenti), verde (se l'ultimo commento è positivo), giallo (se l'ultimo commento è un avviso) o rosso (se l'ultimo commento segnala un pericolo o problema grave). Il colore permette quindi una rapida valutazione.

Strails è in continuo aggiornamento. Ogni volta che ho un po' di tempo libero cerco di aumentarne funzionalità e semplicità d'uso. Ogni consiglio e suggerimento sarà prezioso per renderlo migliore.

## L'indirizzo è:

## https://tst.strails.it

## Pubblicato il codice sorgente

[Lo trovate qui](https://github.com/MaoX17/strails)

Spero possiate aiutarmi e darmi buone idee per migliorare

Grazie per l'attenzione

## Appunti GIT

### Creating a git develop branch

You can list all of your current branches like this:

```
git branch -a
```

This shows all of the local and remote branches. Assuming you only have a single master branch, you'd see the following:

-   master
    remotes/origin/master
    The \* means the current branch.

To create a new branch named develop, use the following command:

```
git checkout -b develop
```

The -b flag creates the branch. Listing the branches now should show:

-   develop
    master
    remotes/origin/master
    Changing branches
    You shouldn't commit anything directly to the master branch. Instead do all your work on the develop branch and then merge develop into master whenever you have a new public release.

You are already in your develop branch, but if you weren't, the way to switch is as follows:

```
git checkout develop
```

That's the same way you create a branch but without the -b.

### Making changes on develop

When making changes, add and commit as usual:

```
git add .
git commit -m "whatever"
```

The first time you push to your remote do it like so:

```
git push -u origin develop
```

The -u flag stands for --set-upstream. After the first time you only need to do it like this:

```
git push
```

### Merging develop to master

Once your develop is ready to merge into master you can do it like so:

First switch to your local master branch:

```
git checkout master
```

To merge develop into master do the following:

```
git merge develop
```

Then push the changes in local master to the remote master:

```
git push
```

Done.

### Deleting a branch

If you don't need the develop branch anymore, or you just want to delete it and start over, you can do the following:

Delete the remote develop branch:

```
git push -d origin develop
```

Then delete the local branch:

```
git branch -d develop
```

The -d means delete.
