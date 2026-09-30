#!/usr/bin/env bash
# Builds sikora-image-quality.zip with readme.txt and PHP files only.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")" && pwd)"
SLUG="sikora-image-quality"
BUILD_DIR="${ROOT}/build/${SLUG}"
ZIP_PATH="${ROOT}/${SLUG}.zip"

rm -rf "${ROOT}/build"
mkdir -p "${BUILD_DIR}/includes"

cp "${ROOT}/readme.txt" "${BUILD_DIR}/"
cp "${ROOT}/sikora-image-quality.php" "${BUILD_DIR}/"
cp "${ROOT}/uninstall.php" "${BUILD_DIR}/"
cp "${ROOT}/includes/"*.php "${BUILD_DIR}/includes/"

rm -f "${ZIP_PATH}"
(
	cd "${ROOT}/build"
	zip -r "${ZIP_PATH}" "${SLUG}"
)

rm -rf "${ROOT}/build"

echo "Created ${ZIP_PATH}"
