/**
 * Brazilian Portuguese translation
 * @author Leandro Carvalho <contato@leandrowebdev.net>
 * @version 2011-07-09
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.pt_BR = {
		translator : 'Leandro Carvalho &lt;contato@leandrowebdev.net&gt;',
		language   : 'Português',
		direction  : 'rtl',
		messages   : {

			/********************************** errors **********************************/
			'error'                : 'Erro',
			'errUnknown'           : 'Erro desconhecido.',
			'errUnknownCmd'        : 'Comando desconhecido.',
			'errJqui'              : 'Configuração inválida do JQuery UI. Verifique os componentes selectable, draggable e droppable incluidos.',
			'errNode'              : 'elFinder requer um elemento DOM para ser criado.',
			'errURL'               : 'Configuração inválida do elFinder! Você deve setar a opção da URL.',
			'errAccess'            : 'Acesso negado.',
			'errConnect'           : 'Incapaz de conectar ao backend.',
			'errAbort'             : 'Conexão abortada.',
			'errTimeout'           : 'Connection timeout.',
			'errNotFound'          : 'Backend não encontrado.',
			'errResponse'          : 'Resposta inválida do backend.',
			'errConf'              : 'Configuração inválida do backend.',
			'errJSON'              : 'Módulo PHP JSON não está instalado.',
			'errNoVolumes'         : 'Não existe nenhum volume legível disponivel.',
			'errCmdParams'         : 'Parâmetro inválido para o comando "$1".',
			'errDataNotJSON'       : 'Dados não estão no formato JSON.',
			'errDataEmpty'         : 'Dados vazios.',
			'errCmdReq'            : 'Requisição do Backend requer nome de comando.',
			'errOpen'              : 'Incapaz de abrir "$1".',
			'errNotFolder'         : 'Objeto não é uma pasta.',
			'errNotFile'           : 'Objeto não é um arquivo.',
			'errRead'              : 'Incapaz de ler "$1".',
			'errWrite'             : 'Incapaz de escrever em "$1".',
			'errPerm'              : 'Permissão negada.',
			'errLocked'            : '"$1" está bloqueado e não pode ser renomeado, movido ou removido.',
			'errExists'            : 'O nome do arquivo "$1" já existe neste local.',
			'errInvName'           : 'Nome do arquivo inválido.',
			'errFolderNotFound'    : 'Pasta não encontrada.',
			'errFileNotFound'      : 'Arquivo não encontrado.',
			'errTrgFolderNotFound' : 'Pasta de destino "$1" não encontrada.',
			'errPopup'             : 'Navegador impediu abertura da janela popup, Para abrir o arquivo desabilite está  opção no navegador.',
			'errMkdir'             : 'Incapaz de criar a pasta "$1".',
			'errMkfile'            : 'Incapaz de criar o arquivo "$1".',
			'errRename'            : 'Incapaz de renomear "$1".',
			'errCopyFrom'          : 'Copia dos arquivos do volume "$1" não permitida.',
			'errCopyTo'            : 'Copia dos arquivos para o volume "$1" não permitida.',
			'errUploadCommon'      : 'Erro no upload.',
			'errUpload'            : 'Incapaz de fazer o upload de "$1".',
			'errUploadNoFiles'     : 'Não foi encontrado nenhum arquivo para upload.',
			'errMaxSize'           : 'Os dados excedem o tamanho máximo permitido.',
			'errFileMaxSize'       : 'Arquivo excede o tamanho máximo permitido.',
			'errUploadMime'        : 'Tipo de arquivo não permitido.',
			'errUploadTransfer'    : '"$1" erro na transferência.', 
			'errSave'              : 'Incapaz de salvar "$1".',
			'errCopy'              : 'Incapaz de copiar "$1".',
			'errMove'              : 'Incapaz de mover "$1".',
			'errCopyInItself'      : 'Incapaz de copiar "$1" nele mesmo.',
			'errRm'                : 'Incapaz de remover "$1".',
			'errExtract'           : 'Incapaz de extrair os arquivos de "$1".',
			'errArchive'           : 'Incapaz de criar o arquivo.',
			'errArcType'           : 'Tipo de arquivo não suportado.',
			'errNoArchive'         : 'Arquivo inválido ou é um tipo sem suporte.',
			'errCmdNoSupport'      : 'Backend não suporta este comando.',

			/******************************* commands names ********************************/
			'cmdarchive'   : 'Criar arquivo',
			'cmdback'      : 'Voltar',
			'cmdcopy'      : 'Copiar',
			'cmdcut'       : 'Cortar',
			'cmddownload'  : 'Baixar',
			'cmdduplicate' : 'Duplicar',
			'cmdedit'      : 'Editar arquivo',
			'cmdextract'   : 'Extrair arquivo de ficheiros',
			'cmdforward'   : 'Avançar',
			'cmdgetfile'   : 'Selecionar arquivos',
			'cmdhelp'      : 'Sobre este software',
			'cmdhome'      : 'Home',
			'cmdinfo'      : 'propriedades',
			'cmdmkdir'     : 'Nova pasta',
			'cmdmkfile'    : 'Novo arquivo de texto',
			'cmdopen'      : 'Abrir',
			'cmdpaste'     : 'Colar',
			'cmdquicklook' : 'Pré-vizualização',
			'cmdreload'    : 'Recarregar',
			'cmdrename'    : 'Renomear',
			'cmdrm'        : 'Deletar',
			'cmdsearch'    : 'Achar arquivos',
			'cmdup'        : 'Ir para o diretório pai',
			'cmdupload'    : 'Fazer upload de arquivo',
			'cmdview'      : 'Vizualizar',

			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Fechar',
			'btnSave'   : 'Salvar',
			'btnRm'     : 'Remover',
			'btnCancel' : 'Cancelar',
			'btnNo'     : 'Não',
			'btnYes'    : 'Sim',

			/******************************** notifications ********************************/
			'ntfopen'     : 'Abrir Pasta',
			'ntffile'     : 'Abrir arquivo',
			'ntfreload'   : 'Recarregar conteudo da pasta',
			'ntfmkdir'    : 'Criar diretório',
			'ntfmkfile'   : 'Criar arquivos',
			'ntfrm'       : 'Deletar arquivos',
			'ntfcopy'     : 'Copiar arquivos',
			'ntfmove'     : 'Mover arquivos',
			'ntfprepare'  : 'Preparar para copiar os arquivos',
			'ntfrename'   : 'Renomear arquivos',
			'ntfupload'   : 'Fazendo o upload dos arquivos',
			'ntfdownload' : 'Baixando os arquivos',
			'ntfsave'     : 'Slvando os arquivos',
			'ntfarchive'  : 'Criando os arquivos',
			'ntfextract'  : 'Extraindo arquivos dos ficheiros',
			'ntfsearch'   : 'Procurando arquivos',
			'ntfsmth'     : 'Fazendo alguma coisa >_<',

			/************************************ dates **********************************/
			'dateUnknown' : 'Desconhecido',
			'Today'       : 'Hoje',
			'Yesterday'   : 'Ontem',
			'Jan'         : 'Jan',
			'Feb'         : 'Fev',
			'Mar'         : 'Mar',
			'Apr'         : 'Abr',
			'May'         : 'Mai',
			'Jun'         : 'Jun',
			'Jul'         : 'Jul',
			'Aug'         : 'Ago',
			'Sep'         : 'Set',
			'Oct'         : 'Out',
			'Nov'         : 'Nov',
			'Dec'         : 'Dez',

			/********************************** messages **********************************/
			'confirmReq'      : 'Confirmação requerida',
			'confirmRm'       : 'Você tem certeza que quer remover os arquivos?<br />Isto não pode ser desfeito!',
			'confirmRepl'     : 'Substituir arquivo velho com este novo?',
			'apllyAll'        : 'Aplicar a todos',
			'name'            : 'Nome',
			'size'            : 'Tamanho',
			'perms'           : 'Permições',
			'modify'          : 'Modificado',
			'kind'            : 'Tipo',
			'read'            : 'Ler',
			'write'           : 'Escrever',
			'noaccess'        : 'Inacessível',
			'and'             : 'e',
			'unknown'         : 'Desconhecido',
			'selectall'       : 'Selecionar todos arquivos',
			'selectfiles'     : 'Selecionar arquivo(s)',
			'selectffile'     : 'Selecionar primeiro arquivo',
			'selectlfile'     : 'Slecionar último arquivo',
			'viewlist'        : 'Exibir como lista',
			'viewicons'       : 'Exibir como ícones',
			'places'          : 'Lugares',
			'calc'            : 'Calcular', 
			'path'            : 'Caminho',
			'aliasfor'        : 'Alias para',
			'locked'          : 'Bloqueado',
			'dim'             : 'Dimesões',
			'files'           : 'Arquivos',
			'folders'         : 'Pastas',
			'items'           : 'Itens',
			'yes'             : 'sim',
			'no'              : 'não',
			'link'            : 'Link',
			'searcresult'     : 'resultados da pesquisa',  
			'selected'        : 'itens selecionados',
			'about'           : 'Sobre',
			'shortcuts'       : 'Atalhos',
			'help'            : 'Ajuda',
			'webfm'           : 'Gerenciador de arquivos web',
			'ver'             : 'Versão',
			'protocol'        : 'Versão do protocolo',
			'homepage'        : 'Home do projeto',
			'docs'            : 'Documentação',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Siga-nos no twitter',
			'facebook'        : 'Junte-se a nós no Facebook',
			'team'            : 'Time',
			'chiefdev'        : 'Desenvolvedor chefe',
			'developer'       : 'Desenvolvedor',
			'contributor'     : 'Contribuinte',
			'maintainer'      : 'Mantenedor',
			'translator'      : 'Tradutor',
			'icons'           : 'Ícones',
			'dontforget'      : 'e não se esqueça de levar sua toalha',
			'shortcutsof'     : 'Atalhos desabilitados',
			'dropFiles'       : 'Solte os arquivos aqui',
			'or'              : 'ou',
			'selectForUpload' : 'Selecione arquivos para upload',
			'moveFiles'       : 'Mover arquivos',
			'copyFiles'       : 'Copiar arquivos',

			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Desconhecio',
			'kindFolder'      : 'Pasta',
			'kindAlias'       : 'Alias',
			'kindAliasBroken' : 'Alias inválido',
			// applications
			'kindApp'         : 'Aplicação',
			'kindPostscript'  : 'Documento Postscript',
			'kindMsOffice'    : 'Documento Microsoft Office',
			'kindMsWord'      : 'Documento Microsoft Word',
			'kindMsExcel'     : 'Documento Microsoft Excel',
			'kindMsPP'        : 'Apresentação Microsoft Powerpoint',
			'kindOO'          : 'Documento Open Office',
			'kindAppFlash'    : 'Aplicação Flash',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Arquivo Bittorrent',
			'kind7z'          : 'Arquivo 7z',
			'kindTAR'         : 'Arquivo TAR',
			'kindGZIP'        : 'Arquivo GZIP',
			'kindBZIP'        : 'Arquivo BZIP',
			'kindZIP'         : 'Arquivo ZIP',
			'kindRAR'         : 'Arquivo RAR',
			'kindJAR'         : 'Arquivo JAR',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'Pacote RPM',
			// texts
			'kindText'        : 'Arquivo de texto',
			'kindTextPlain'   : 'Texto simples',
			'kindPHP'         : 'PHP',
			'kindCSS'         : 'CSS',
			'kindHTML'        : 'Documento HTML',
			'kindJS'          : 'Javascript',
			'kindRTF'         : 'Formato Rich Text',
			'kindC'           : 'C',
			'kindCHeader'     : 'C cabeçalho',
			'kindCPP'         : 'C++',
			'kindCPPHeader'   : 'C++ cabeçalho',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python',
			'kindJava'        : 'Java',
			'kindRuby'        : 'Ruby',
			'kindPerl'        : 'Perl script',
			'kindSQL'         : 'SQL',
			'kindXML'         : 'Documento XML',
			'kindAWK'         : 'AWK',
			'kindCSV'         : 'Valores separados por vírgula',
			'kindDOCBOOK'     : 'Documento Docbook XML',
			// images
			'kindImage'       : 'Imagem',
			'kindBMP'         : 'Imagem BMP',
			'kindJPEG'        : 'Imagem JPEG',
			'kindGIF'         : 'Imagem GIF',
			'kindPNG'         : 'Imagem PNG',
			'kindTIFF'        : 'Imagem TIFF',
			'kindTGA'         : 'Imagem TGA',
			'kindPSD'         : 'Imagem Adobe Photoshop',
			'kindXBITMAP'     : 'Imagem X bitmap',
			'kindPXM'         : 'Imagem Pixelmator',
			// media
			'kindAudio'       : 'Audio media',
			'kindAudioMPEG'   : 'Audio MPEG',
			'kindAudioMPEG4'  : 'Audio MPEG-4',
			'kindAudioMIDI'   : 'Audio MIDI',
			'kindAudioOGG'    : 'Audio Ogg Vorbis',
			'kindAudioWAV'    : 'Audio WAV',
			'AudioPlaylist'   : 'MP3 playlist',
			'kindVideo'       : 'Video media',
			'kindVideoDV'     : 'DV filme',
			'kindVideoMPEG'   : 'Video MPEG',
			'kindVideoMPEG4'  : 'Video MPEG-4',
			'kindVideoAVI'    : 'Video AVI',
			'kindVideoMOV'    : 'Quick Time movie',
			'kindVideoWM'     : 'Video Windows Media',
			'kindVideoFlash'  : 'Video Flash',
			'kindVideoMKV'    : 'Video Matroska',
			'kindVideoOGG'    : 'Video Ogg'
		}
	}
}

