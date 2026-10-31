// Exécuter depuis le dossier de livraison contenant htdocs, avant téléversement.
import {randomBytes} from 'node:crypto';
import {readFileSync,writeFileSync,existsSync} from 'node:fs';
import {resolve} from 'node:path';
const dir=resolve(process.argv[2]||'htdocs/_app');
if(existsSync(resolve(dir,'.env')))throw new Error('.env existe déjà : ne pas régénérer la clé d’un site installé.');
let env=readFileSync(resolve(dir,'.env.example'),'utf8');
env=env.replace(/^APP_KEY=.*$/m,'APP_KEY=base64:'+randomBytes(32).toString('base64'));
env=env.replace(/^INSTALLATION_TOKEN=.*$/m,'INSTALLATION_TOKEN='+randomBytes(32).toString('hex'));
writeFileSync(resolve(dir,'.env'),env,{mode:0o600});
console.log('.env créé avec des clés propres à cette installation. Renseignez DB_* dans ce fichier local avant le téléversement. Aucune clé affichée.');
