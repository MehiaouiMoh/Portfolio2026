#!/usr/bin/env bash
# Usage : ./scripts/snapshot.sh http://localhost:8000 snapshots/old
BASE=$1
OUT=$2
mkdir -p "$OUT"

# Mets ici toutes tes routes GET de l'inventaire
ROUTES=(
  # Informations
  "/api/informations"
  "/api/informations/1"

  # Compétences & outils
  "/api/categorie-competences"
  "/api/categorie-competences/1"
  "/api/competences"
  "/api/competences/1"
  "/api/langages"
  "/api/langages/1"
  "/api/frameworks"
  "/api/frameworks/1"
  "/api/logiciels"
  "/api/logiciels/1"

  # Scolarité
  "/api/scolarite"
  "/api/scolarite/1"
  "/api/introduction-ecole"
  "/api/introduction-ecole/1"
  "/api/modules"
  "/api/modules/1"
  "/api/modules/1/enseignements"
  "/api/enseignements"
  "/api/enseignements/1"

  # Projets
  "/api/projets"
  "/api/projets/1"
  "/api/images-projet"
  "/api/images-projet/1"
  "/api/projet-langage"
  "/api/projet-langage/1"

  # Carrière & expériences
  "/api/carrieres"
  "/api/carrieres/1"
  "/api/carriere-competence"
  "/api/carriere-competence/1"
  "/api/experiences"
  "/api/experiences/1"

  # Protégées (JWT requis) — décommenter si le script envoie le cookie/Bearer
  # "/api/auth/me"
  # "/api/visites"
  # "/api/visites/1"

  # --- Cas d'erreur ---
  "/api/projets/99999"      # 404 : ressource introuvable
  "/api/visites"            # 401 : route protégée appelée sans token
)

for r in "${ROUTES[@]}"; do
  name=$(echo "$r" | sed 's#^/api/##; s#/#_#g')
  code=$(curl -s -o "$OUT/$name.json" -w "%{http_code}" "$BASE$r")

  if [ "$code" = "000" ]; then
    echo "ERREUR connexion impossible → $BASE$r (serveur lancé ?)"
    exit 1
  fi

  # Normalisation : clés triées, caractères décodés → diffs lisibles
  jq -S . "$OUT/$name.json" > "$OUT/$name.tmp" && mv "$OUT/$name.tmp" "$OUT/$name.json"

  # On garde aussi le code HTTP : il fait partie du contrat
  echo "$code" > "$OUT/$name.status"
  echo "$code  $r"
done