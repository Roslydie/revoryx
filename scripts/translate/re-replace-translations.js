import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// Obtenir le répertoire courant
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Chemin du fichier fr.json
const enFilePath = path.join(__dirname, '../../resources/js/Components/plugins/locales/fr.json');

// Charger les traductions existantes
const translations = JSON.parse(fs.readFileSync(enFilePath, 'utf-8'));

// Fonction pour remplacer les appels _e()
function replaceTranslationsInFile(filePath) {
    console.log(`Fichier parcouru : ${filePath}`);
    let content = fs.readFileSync(filePath, 'utf-8');

    // Remplacement des appels _e("value") ou _e("key", "value") // for /_e\s*\(\s*['"`](.+?)['"`]\s*(?:,\s*['"`](.+?)['"`])?\s*\)/g
    // content = content.replace(/_e\((['"`])((?:\\\1|.)*?)\1(?:\s*,\s*(['"`])((?:\\\3|.)*?)\3)?\)/g, (match, quote1, keyOrValue, quote2, value) => {

    // Remplacement des appels _e("value") ou _e("key", "value") // for /_e\s*\(\s*['"`]([\s\S]+?)['"`]\s*(?:,\s*['"`]([\s\S]+?)['"`])?\s*\)/gs
    content = content.replace(/_e\s*\(\s*['"`]([\s\S]+?)['"`]\s*(?:,\s*['"`]([\s\S]+?)['"`])?\s*\)/gs, (match, keyOrValue, value) => {
        console.log(`Correspondance trouvée : ${match}`);
        // Si le second paramètre (value) existe, comparer avec les traductions
        const actualKey = keyOrValue; // Prendre `value` si elle existe, sinon `keyOrValue`
        const translationValue = translations[actualKey];

        if (translationValue) {
            // check if the translation value contains line breaks
            if (translationValue.includes('\n')) {
                console.log(`Remplacement éffectué : ${translationValue}`);
                return `_e(\`${translationValue}\`)`; // Remplacer par la valeur trouvée
            } else {
                console.log(`Remplacement éffectué : ${translationValue}`);
            return `_e("${translationValue}")`; // Remplacer par la valeur trouvée
            }
        }

        // Retourner l'expression inchangée si aucune correspondance n'est trouvée
        return match;
    });

    // Écrire le fichier modifié
    fs.writeFileSync(filePath, content, 'utf-8');
}

// Fonction pour parcourir les fichiers
function processFilesInDirectory(directory) {
    const files = fs.readdirSync(directory);

    files.forEach(file => {
        const fullPath = path.join(directory, file);

        if (fs.lstatSync(fullPath).isDirectory()) {
            processFilesInDirectory(fullPath); // Appel récursif pour les sous-dossiers
        } else if (file.endsWith('.js') || file.endsWith('.vue')) {
            replaceTranslationsInFile(fullPath); // Remplacement des traductions
        }
    });
}

// Exécution du script
const projectDir = path.resolve(__dirname, '../../resources/js'); // Remplacer par le chemin de votre projet
processFilesInDirectory(projectDir);