# Incident du 16/11/2026 — Base de production écrasée

## Résumé
(1-2 phrases : quoi, combien de temps, quel impact)

## Chronologie
- 22:17 — Mauvaise manipulation, copie du dossier sqlite dans le repository et non dans le dossier de dploiement
- 22:46 — Supression de la base de données sqlite suite à une supression d'un fichier dans le repository dedié au deploiement
- 23:10 — Recupération d'un backup, copie de ce backup dans le dossier de deploiement

## Cause racine
(Pourquoi c'est arrivé — creuse jusqu'au vrai « pourquoi »)
Cause : mauvaise configuration du cpanel. Il deployait tout ce qui etait dans le repository sur le dossier officiel

## Ce qui a bien fonctionné
(Indice : 22:17)

## Actions correctives
- [x] Copie du fichier sqlite avant la dite modification (nommé .bak)
- [x] Récuperation du backup et copie dans le dossier offciel
- [x] Modification du cpanel afin qu'il ne deploit que les fichiers necessaires