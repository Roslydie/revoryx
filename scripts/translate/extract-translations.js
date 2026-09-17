import fs from 'fs';
import path from 'path';

import { fileURLToPath } from 'url';
import { dirname } from 'path';

// Obtenir le répertoire du fichier actuel
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);


// Chemin du fichier en.json
const enFilePath = path.join(__dirname, '../../resources/js/Components/plugins/locales/en.json');

// Charger les traductions existantes
const translations = JSON.parse(fs.readFileSync(enFilePath, 'utf-8'));

// Dossier source à scanner
const SOURCE_DIR = path.resolve(__dirname, '../../resources/js/Components');
// Dossier de sortie pour les fichiers de traduction
const OUTPUT_DIR = path.resolve(__dirname, '../../resources/js/Components/plugins/locales');

// Récupérer le nom du fichier spécifique s'il est fourni
const specificFile = process.argv[2];

// Expression régulière pour détecter les appels à _e()
// const regex = /_e\s*\(\s*['"`](.+?)['"`]\s*(?:,\s*['"`](.+?)['"`])?\s*\)/g;
const regex = /_e\s*\(\s*['"`]([\s\S]+?)['"`]\s*(?:,\s*['"`]([\s\S]+?)['"`])?\s*\)/gs;


let i = 0;

function normalizeSlashes(pathStr) {
    return pathStr.replace(/\\/g, '/');
}

function scanDirectory(directory) {
    const files = fs.readdirSync(directory);

    files.forEach(file => {
        const fullPath = path.join(directory, file);

        if (fs.statSync(fullPath).isDirectory()) {
            // Ne parcourir les sous-dossiers que si aucun fichier spécifique n'est demandé
            // ou si le fichier spécifique pourrait être dans ce dossier
            if (!specificFile || specificFile.includes('/')) {
                scanDirectory(fullPath);
            }
        } else if ((file.endsWith('.js') || file.endsWith('.vue')) &&
            (!specificFile || file === specificFile ||
                normalizeSlashes(fullPath).includes(normalizeSlashes(specificFile)))) {
            console.log(`Scanning file: ${fullPath}`);
            // Lire le contenu du fichier
            const content = fs.readFileSync(fullPath, 'utf8');
            let match;
            while ((match = regex.exec(content)) !== null) {
                // Utiliser le nom du fichier, l'heure
                // date and file name to make the key unique
                const uniq = file + '_' + new Date().getTime() + '_' + i++;

                // si on a deux match (key, value) match[1] = key, match[2] = value
                if (match[2]) {

                    translations[match[1]] = match[2];

                    // si on a un seul match (key) match[1] = key et on a pas de traduction
                } else if (!translations[match[1]]) {

                    // Ajouter une clé uniq avec la valeur de la clé comme traduction
                    translations[uniq] = match[1];

                }
            }
        }
    });
}

// Vérifier si le fichier spécifique existe si fourni
if (specificFile) {
    const normalizedSpecificFile = normalizeSlashes(specificFile);
    // Vérifier si le chemin complet est fourni
    const fullPath = normalizedSpecificFile.includes(normalizeSlashes(SOURCE_DIR))
        ? specificFile
        : path.join(SOURCE_DIR, specificFile);

    console.log(`Looking for specific file: ${fullPath}`);

    if (!fs.existsSync(fullPath)) {
        console.error(`File not found: ${specificFile}`);
        process.exit(1);
    }
}

// Scanner les fichiers
scanDirectory(SOURCE_DIR);

// Créer le dossier locales s'il n'existe pas
if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR);
}

// Générer les fichiers de traduction pour en
['en'].forEach(locale => {
    const filePath = path.join(OUTPUT_DIR, `${locale}.json`);
    const existingTranslations = fs.existsSync(filePath)
        ? JSON.parse(fs.readFileSync(filePath, 'utf8'))
        : {};
    const mergedTranslations = { ...existingTranslations, ...translations };

    fs.writeFileSync(filePath, JSON.stringify(mergedTranslations, null, 2));
    console.log(`Fichier généré : ${filePath}`);
});