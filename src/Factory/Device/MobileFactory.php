<?php
/**
 * This file is part of the browser-detector package.
 *
 * Copyright (c) 2012-2018, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace BrowserDetector\Factory\Device;

use BrowserDetector\Cache\CacheInterface;
use BrowserDetector\Factory\DeviceFactoryInterface;
use BrowserDetector\Loader\DeviceLoaderFactory;
use Psr\Log\LoggerInterface;

class MobileFactory implements DeviceFactoryInterface
{
    private $factories = [
        '/startrail|starxtrem|starshine|staraddict|starnaute|startext|startab/i' => 'sfr', // also includes the 'Tunisie Telecom' and the 'Altice' branded devices
        '/HTC|Sprint (APA|ATP)|ADR(?!910L)[a-z0-9]+|NexusHD2|Amaze[ _]4G[);\/ ]|(Desire|Sensation|Evo ?3D|IncredibleS|Wildfire|Butterfly)[ _]?([^;\/]+) Build|One ?[XELSV\+]+[);\/ ]|SPV E6[05]0|One M8|X525a|PG86100|PC36100|XV6975|PJ83100[);\/ ]|2PYB2|0PJA10|T\-Mobile_Espresso/' => 'htc',
        // @todo: rules with company name in UA
        '/hiphone/i' => 'hiphone',
        '/technisat/i' => 'technisat',
        '/samsung[is \-;\/]|gt\-i8750/i' => 'samsung',
        '/nokia/i' => 'nokia',
        '/blackberry/i' => 'rim',
        '/asus|nexus[ _]?7|padfone|transformer|tf300t|slider sl101|me302(c|kl)|me301t|me371mg|me17(1|2v|3x)|eee_701|tpad_10|tx201la|p01t_1|(k0[01][0-9a-z]|z00d|z00yd|p(00[8ac]|01y|02[12347]) build|z017d)[);\/ ]/i' => 'asus',
        '/feiteng/i' => 'feiteng',
        '/mypad (1000|750) ?hd/i' => 'yooz',
        '/(myphone|mypad|mytab)[ _][^;\/]+ build|cube_lte|mytab10ii/i' => 'myphone', // must be before Cube
        '/cube|(u[0-9]+gt|k8gt)|i1\-3gd|i15\-tcl/i' => 'cube',
        '/LG/' => 'lg',
        '/pantech/i' => 'pantech',
        '/touchpad\/\d+\.\d+|hp-tablet|hp ?ipaq|webos.*p160u|slate|hp [78]|compaq [7|8]|hp; [^;\/)]+|pre\/|pixi|palm|cm_tenderloin/i' => 'hp',
        '/hisense [^);\/]+|hs-(g|u|eg?|i|l|t|x)[0-9]+[a-z0-9\-]*|e270bsa|m470bs[ae]|e2281|eg680|f5281|u972|e621t|w2003/i' => 'hisense',
        '/sony/i' => 'sony',
        '/accent/i' => 'accent',
        '/lenovo/i' => 'lenovo',
        '/zte|racer/i' => 'zte',
        '/acer|da241hl/i' => 'acer',
        '/amazon/i' => 'amazon',
        '/amoi/i' => 'amoi',
        '/CCE /' => 'cce',
        '/blaupunkt|atlantis[_ ](1001a|1010a|a10\.g402)|discovery[_ ](102c|111c|1000c|1001a?)|endeavour[_ ](785|101[glm]|1000|1001|101[03]|1100)|polaris[_ ]803|end_101g\-test/i' => 'blaupunkt', // must be before Onda
        '/ONDA/' => 'onda',
        '/archos|a101it|a7eb|a70bht|a70cht|a70hb|a70s|a80ksc|a35dm|a70h2|a50ti/i' => 'archos',
        '/irulu/i' => 'irulu',
        '/symphony/i' => 'symphony',
        '/turbo\-x/i' => 'turbo-x',
        '/spice/i' => 'spice',
        '/arnova/i' => 'arnova',
        '/coby/i' => 'coby',
        '/o\+|oplus/i' => 'oplus',
        '/goly/i' => 'goly',
        '/WM[0-9]{4}/' => 'wondermedia',
        '/comag/i' => 'comag',
        '/coolpad/i' => 'coolpad',
        '/cosmote/i' => 'cosmote',
        '/creative/i' => 'creative',
        '/cubot/i' => 'cubot',
        '/dell/i' => 'dell',
        '/denver|ta[cdq]-[0-9]+/i' => 'denver',
        '/sharp|shl25/i' => 'sharp',
        '/flytouch/i' => 'flytouch',
        '/n\-06e|n[79]05i/i' => 'nec', // must be before docomo
        '/docomo/i' => 'docomo',
        '/easypix|easypad|easyphone|junior 4\.0/i' => 'easypix',
        '/xoro/i' => 'xoro',
        '/memup/i' => 'memup',
        '/fujitsu/i' => 'fujitsu',
        '/honlin/i' => 'honlin',
        '/huawei/i' => 'huawei',
        '/micromax/i' => 'micromax',
        '/explay|actived[ _]|atlant |informer[ _][0-9]+|cinematv 3g|surfer[ _][0-9\.]|squad[ _][0-9\.]|onliner[1-3]|rioplay|m1_plus|d7\.2 3g|art 3g/i' => 'explay',
        '/oneplus/i' => 'oneplus',
        '/kingzone/i' => 'kingzone',
        '/goophone/i' => 'goophone',
        '/g\-tide/i' => 'gtide',
        '/turbo ?pad/i' => 'turbopad',
        '/haier/i' => 'haier',
        '/hummer/i' => 'hummer',
        '/oysters/i' => 'oysters',
        '/gfive/i' => 'gfive',
        '/iconbit/i' => 'iconbit',
        '/sxz/i' => 'sxz',
        '/aoc/i' => 'aoc',
        '/jay\-tech/i' => 'jaytech',
        '/jolla/i' => 'jolla',
        '/kazam/i' => 'kazam',
        '/kddi/i' => 'kddi',
        '/kobo/i' => 'kobo',
        '/lenco/i' => 'lenco',
        '/le ?pan/i' => 'lepan',
        '/logicpd/i' => 'logicpd',
        '/medion/i' => 'medion',
        '/(ta10ca3|tm105a?|tr10cs1)[);\/ ]/i' => 'ecs',
        '/gem[0-9]+[a-z]*/i' => 'gemini',
        '/meizu/i' => 'meizu',
        '/minix/i' => 'minix',
        '/allwinner/i' => 'allwinner',
        '/supra/i' => 'supra',
        '/prestigio/i' => 'prestigio',
        '/mobistel/i' => 'mobistel',
        '/moto/i' => 'motorola',
        '/nintendo/i' => 'nintendo',
        '/(q7a\+?)[);\/ ]/i' => 'crius-mea',
        '/crosscall|odyssey_plus|odyssey s1|trekker-[msx][123]/i' => 'crosscall',
        '/odys/i' => 'odys',
        '/oppo/i' => 'oppo',
        '/panasonic/i' => 'panasonic',
        '/pandigital/i' => 'pandigital',
        '/phicomm/i' => 'phicomm',
        '/pomp/i' => 'pomp',
        '/qmobile/i' => 'qmobile',
        '/sanyo/i' => 'sanyo',
        '/siemens/i' => 'siemens',
        '/benq/i' => 'siemens',
        '/sagem/i' => 'sagem',
        '/ouya/i' => 'ouya',
        '/trevi/i' => 'trevi',
        '/cowon/i' => 'cowon',
        '/digma[_ ][^;\/]+ build|hit ht707[10]mg|citi 1902 3g|citi [a-z0-9]+ 3g c[st]500[67]pg|idjd7|idrq10[ _]3g|idxd8 3g|idnd7|hit 4g ht7074ml|idx5|idx10|idx7|mvm900h(wz|c)|mvm908hcz|idxd10 3g|idxd[45]|idxq5|idxd7[_ ]3g|ps604m|pt452e|linx a400 3g lt4001pg|linx c500 3g lt5001pg|linx ps474s|ns6902ql|ns9797mg|(optima|platina|plane)[ _][em]?([0-9\.st]+|prime)([ _][43]g)?|tt7026mw|vox[ _][0-9\.a-z]+[_ ][43]g/i' => 'digma',
        '/hudl 2|hudl ht7s3/i' => 'tesco',
        '/homtom|ht[0-9]{1,2} ?(pro)?/i' => 'homtom',
        '/hosin/i' => 'hosin',
        '/hasee/i' => 'hasee',
        '/tecno/i' => 'tecno',
        '/intex/i' => 'intex',
        '/mt\-gt\-a9500|gt\-a7100/i' => 'htm', // must be before samsung (gt rule)
        '/gt\-h/i' => 'feiteng',
        '/gt\-9000/i' => 'star',
        '/sc\-74jb|sc\-91mid/i' => 'supersonic',
        '/(gt|sam|sc|sch|sec|sgh|shv|shw|sm|sph|continuum|ek|yp)\-/i' => 'samsung', // must be before orange and sprint
        '/sprint/i' => 'sprint',
        '/gionee/i' => 'gionee',
        '/videocon/i' => 'videocon',
        '/gigaset|gs55\-6|gs53\-6|gs270 plus|gs370_plus/i' => 'gigaset',
        '/(dns|airtab)[ _\-]([^;\/]+)build|s4503q|s4505m/i' => 'dns',
        '/kyocera/i' => 'kyocera',
        '/texet/i' => 'texet',
        '/s\-tell/i' => 'stell',
        '/bliss/i' => 'bliss',
        '/poly ?pad/i' => 'polypad',
        '/doov/i' => 'doov',
        '/ov\-|solution 7iii|qualcore 1010/i' => 'overmax',
        '/smart ?(tab(10|7)|4g|ultra 6)/i' => 'zte',
        '/smart tab 4g/i' => 'lenovo',
        '/smart tab 4|vfd [0-9]{3}|985n|vodafone smart 4 max|smart 4 turbo/i' => 'vodafone',
        '/idea(tab|pad)|smart ?tab|thinkpad/i' => 'lenovo',
        '/alcatel|alc[a-z0-9]+|one[ _]?touch|idol3|(4003a|4013[dmx]|4017x|4024d|4027[dx]|4032d|4034[df]|5010[dx]|5015[adx]|5016a|5017a|5019d|5025[dg]|5026d|5045[dtx]|5022[dx]|5023f|5038x|5042[ad]|5051[dx]|5054[dnswx]|5056[dx]|5065[dnx]|5070[dx]|5080x|5095[biky]|6016[dx]|6036y|6037y|6039[hky]|6043d|6044d|6045[hky]|6050[afy]|6055[kp]|6070[koy]|7048x|7040n|7070x|8030y|8050d|9001[dx]|9002x|9003x|9005x|9008x|9010x|9022x|i213|i216x|v860|vodafone (smart|785|875|975n)|vf\-(795|895n)|m812c|telekom puls|ot\-[89][09][0-9])[);\/ ]/i' => 'alcatel',
        '/tolino/i' => 'tolino',
        '/toshiba/i' => 'toshiba',
        '/trekstor/i' => 'trekstor',
        '/viewsonic/i' => 'viewsonic',
        '/view(pad|phone)/i' => 'viewsonic',
        '/wiko/i' => 'wiko',
        '/BLU/' => 'blu',
        '/vivo (iv|4\.65)/i' => 'blu',
        '/vivo/i' => 'vivo',
        '/haipai/i' => 'haipai',
        '/megafon/i' => 'megafon',
        '/yuanda/i' => 'yuanda',
        '/pocketbook/i' => 'pocketbook',
        '/goclever|quantum|aries|insignia|orion_|elipso|terra_101|orion7o|tq[0-9]{3}/i' => 'goclever',
        '/senseit/i' => 'senseit',
        '/twz/i' => 'twz',
        '/i\-mobile/i' => 'imobile',
        '/evercoss/i' => 'evercoss',
        '/dino/i' => 'dino',
        '/shaan|iball/i' => 'shaan',
        '/modecom/i' => 'modecom',
        '/kiano/i' => 'kiano',
        '/philips/i' => 'philips',
        '/infinix/i' => 'infinix',
        '/infocus/i' => 'infocus',
        '/pentagram/i' => 'pentagram',
        '/smartfren/i' => 'smartfren',
        '/gce x86 phone/i' => 'google',
        '/ngm/i' => 'ngm',
        '/orange (hi 4g|reyo)/i' => 'zte', // must be before orange
        '/orange/i' => 'orange',
        '/spv/i' => 'orange',
        '/mot/i' => 'motorola',
        '/(pgn\-?[456][012][0-9]|pkt\-?301|phs\-601)[;\/\) ]|ctab[^\/;]+ build/i' => 'condor',
        '/beeline pro/i' => 'zte',
        '/beeline/i' => 'beeline',
        '/axgio/i' => 'axgio',
        '/zopo/i' => 'zopo',
        '/malata/i' => 'malata',
        '/starway/i' => 'starway',
        '/starmobile/i' => 'starmobile',
        '/logicom/i' => 'logicom',
        '/gigabyte/i' => 'gigabyte',
        '/qumo/i' => 'qumo',
        '/celkon/i' => 'celkon',
        '/bravis/i' => 'bravis',
        '/fnac/i' => 'fnac',
        '/tcl/i' => 'tcl',
        '/radxa/i' => 'radxa',
        '/xolo/i' => 'xolo',
        '/dragon touch/i' => 'dragontouch',
        '/ramos/i' => 'ramos',
        '/woxter/i' => 'woxter',
        '/k\-?touch/i' => 'ktouch',
        '/mastone/i' => 'mastone',
        '/nuqleo/i' => 'nuqleo',
        '/wexler/i' => 'wexler',
        '/exeq/i' => 'exeq',
        '/4good/i' => 'fourgood',
        '/utstar/i' => 'utstarcom',
        '/walton/i' => 'walton',
        '/quadro/i' => 'quadro',
        '/xiaomi/i' => 'xiaomi',
        '/pipo/i' => 'pipo',
        '/tesla/i' => 'tesla',
        '/doro/i' => 'doro',
        '/captiva/i' => 'captiva',
        '/energy[ _-]?[^;\/]+ build/i' => 'energy-sistem',
        '/elephone[ _\-][^\/;]+ build|p[369]000( ?pro| ?plus|\+| ?02)? build|p8_mini/i' => 'elephone',
        '/cyrus/i' => 'cyrus',
        '/wopad/i' => 'wopad',
        '/anka/i' => 'anka',
        '/lemon/i' => 'lemon',
        '/lava/i' => 'lava',
        '/sop\-/i' => 'sop',
        '/vsun/i' => 'vsun',
        '/advan/i' => 'advan',
        '/velocity/i' => 'velocitymicro',
        '/allview/i' => 'allview',
        '/tagi/i' => 'tagi',
        '/avvio/i' => 'avvio',
        '/e\-boda/i' => 'eboda',
        '/ergo/i' => 'ergo',
        '/pulid/i' => 'pulid',
        '/dexp/i' => 'dexp',
        '/keneksi/i' => 'keneksi',
        '/reeder/i' => 'reeder',
        '/globex/i' => 'globex',
        '/oukitel/i' => 'oukitel',
        '/itel/i' => 'itel',
        '/wileyfox/i' => 'wileyfox',
        '/morefine/i' => 'morefine',
        '/vernee/i' => 'vernee',
        '/iocean/i' => 'iocean',
        '/intki/i' => 'intki',
        '/i\-joy/i' => 'ijoy',
        '/inq/i' => 'inq',
        '/inew/i' => 'inew',
        '/iberry/i' => 'iberry',
        '/koobee/i' => 'koobee',
        '/kingsun/i' => 'kingsun',
        '/komu/i' => 'komu',
        '/kopo/i' => 'kopo',
        '/koridy/i' => 'koridy',
        '/kumai/i' => 'kumai',
        '/konrow/i' => 'konrow',
        '/nexus ?[45]/i' => 'lg', // must be before MTC
        '/MTC/' => 'mtc',
        '/eSTAR/' => 'estar',
        '/flipkart|xt811/i' => 'flipkart',
        '/XT[0-9]{3,4}|WX[0-9]{3}|MB[0-9]{3}/' => 'motorola',
        '/UMI/' => 'umi',
        '/NTT/' => 'nttsystem',
        '/lingwin/i' => 'lingwin',
        '/boway/i' => 'boway',
        '/sprd/i' => 'sprd',
        '/NEC\-/' => 'nec',
        '/thl[ _]/i' => 'thl',
        '/iq1055/i' => 'mls', // must be before Fly
        '/fly[ _]|flylife|phoenix 2|fs50[1-9]|fs511|fs551|fs40[1-7]|fs452|fs451|fs454|4fs06|meridian-|iq[0-9]{3,}i?[ _]?(quad|firebird|quattro|turbo|magic)?( build|[;\/\)])/i' => 'fly',
        '/bmobile[ _]/i' => 'bmobile',
        '/hlv-t[a-z0-9]+/i' => 'hi-level',
        '/HL/' => 'honlin',
        '/mtech/i' => 'mtech',
        '/lexand/i' => 'lexand',
        '/meeg/i' => 'meeg',
        '/mofut/i' => 'mofut',
        '/majestic/i' => 'majestic',
        '/mlled/i' => 'mlled',
        '/m\.t\.t\./i' => 'mtt',
        '/meu/i' => 'meu',
        '/noain/i' => 'noain',
        '/nomi/i' => 'nomi',
        '/nexian/i' => 'nexian',
        '/ouki/i' => 'ouki',
        '/opsson/i' => 'opsson',
        '/qilive/i' => 'qilive',
        '/quechua/i' => 'quechua',
        '/stx/i' => 'stonex',
        '/sunvan/i' => 'sunvan',
        '/vollo/i' => 'vollo',
        '/bluboo/i' => 'bluboo',
        '/nuclear/i' => 'nuclear',
        '/uniscope/i' => 'uniscope',
        '/voto/i' => 'voto',
        '/la\-m1|la2\-t/i' => 'beidou',
        '/yusun/i' => 'yusun',
        '/ytone/i' => 'ytone',
        '/zeemi/i' => 'zeemi',
        '/bush/i' => 'bush',
        '/B[iI][rR][dD][ _\-]/' => 'bird',
        '/cherry|flare2x|fusion bolt/i' => 'cherry-mobile',
        '/desay/i' => 'desay',
        '/datang/i' => 'datang',
        '/EBEST/' => 'ebest',
        '/ETON/' => 'eton',
        '/evolveo/i' => 'evolveo',
        '/telenor[ _]/i' => 'telenor',
        '/concorde/i' => 'concorde',
        '/readboy/i' => 'readboy',
        '/sencor/i' => 'sencor',
        '/axxion/i' => 'axxion',
        '/cnm[ \-](touchpad|tp)[ \-]([0-9\.]+)/i' => 'cnm',
        '/dslide ?[^;\/]+ build/i' => 'danew',
        '/grundig|gr?-tb[0-9]+[a-z]*|portalmmm\/2\.0 g/i' => 'grundig',
        '/hyundai|ultra air|ultra live/i' => 'hyundai',
        '/casper/i' => 'casper',
        '/noa[ _]/i' => 'noa',
        // @todo: general rules
        '/auxus/i' => 'iberry',
        '/lumia|maemo rx|portalmmm\/2\.0 n7|portalmmm\/2\.0 nk|nok[0-9]+|symbian.*\s([a-z0-9]+)$|rx-51 n900|rm-(1031|104[25]|106[234567]|107[234567]|1089|109[029]|1109|111[34]|1127|1141|1154)|ta-[0-9]{4} build|(adr|android) 5\.[01].* n1|5130c\-2|arm; 909|id336|genm14/i' => 'nokia', // also includes the 'Microsoft' branded Lumia devices,
        '/(adr|android) 4\.4.* n1/i' => 'newsman',
        '/(adr|android) 4\.2.* n1/i' => 'oppo',
        '/(adr|android) 4\.0.* n1/i' => 'tizzbird',
        '/rm\-(997|560)/i' => 'rossmoor',
        '/gtx75/i' => 'utstarcom',
        '/galaxy s3 ex/i' => 'hdc',
        '/nexus 6p/i' => 'huawei',
        '/nexus 6/i' => 'motorola',
        '/nexus ?(one|9|evohd2|hd2)/i' => 'htc',
        '/galaxy|nexus|i(7110|9100|9300)|blaze|s8500/i' => 'samsung',
        '/iconia|liquid/i' => 'acer',
        '/playstation/i' => 'sony',
        '/kindle|silk|kf(tt|ot|jwi|sowi|thwi|apwa|aswi|apwi|dowi|auwi|giwi|tbwi|mewi|fowi|sawi|sawa|suwi|arwi|thwa|jwa)|sd4930ur|fire2/i' => 'amazon',
        '/zoom2|nook ?color/i' => 'logicpd',
        '/nook|bn[tr]v[0-9]+/i' => 'barnesnoble',
        '/playbook|rim tablet|bb10|stv100|bb[ab]100\-2|sth100\-2|bbd100\-1/i' => 'rim',
        '/b15/i' => 'caterpillar',
        '/cat ?(nova|stargate|tablet|helix)/i' => 'catsound',
        '/MID1014/' => 'micromax',
        '/MID0714|MIDC|PMID/' => 'polaroid',
        '/MID(1024|1125|1126|1045|1048|1060|1065|4331|7012|7015A?|7016|7022|7032|7035|7036|7042|7047|7048|7052|7065|7120|8024|8042|8048|8065|8125|8127|8128|9724|9740|9742)/' => 'coby',
        '/(?<!\/)MID713|MID(06[SN]|08[S]?|12|13|14|15|701|702|703|704|705(DC)?|706[AS]?|707|708|709|711|712|714|717|781|801|802|901|1001|1002|1003|1004( 3G)?|1005|1009|1010|7802|9701|9702)/' => 'manta',
        '/P[AS]P|PM[PT]/' => 'prestigio',
        '/smartpad7503g|smartpad970s2(3g)?|m[_\-][mp]p[0-9a-z]+|m\-ipro[0-9a-z]+/i' => 'mediacom',
        '/nbpc724/i' => 'coby',
        '/wtdr1018/i' => 'comag',
        '/ziilabs|ziio7/i' => 'creative',
        '/connect(7pro|8plus)/i' => 'odys',
        '/[0-9]{3}SH|SH\-?[0-9]{2,4}[CDEFUW]/' => 'sharp',
        '/p900i/i' => 'docomo',
        '/smart\-e5/i' => 'efox',
        '/telepad/i' => 'xoro',
        '/slidepad|sp[0-9]{3}|spng[0-9]{3}/i' => 'memup',
        '/epad|p7901a/i' => 'zenithink',
        '/p7mini/i' => 'huawei',
        '/m532|m305|f\-0[0-9][def]|is11t/i' => 'fujitsu',
        '/hsg[0-9]{4}|sn10t1|sn97t41w|sn1at71w\(b\)|sn70t51a|sn70t31?|t7-qc/i' => 'hannspree',
        '/PC1088/' => 'honlin',
        '/INM[0-9]{3,4}/' => 'intenso',
        '/sailfish/i' => 'jolla',
        '/lifetab/i' => 'medion',
        '/cynus/i' => 'mobistel',
        '/DARK(MOON|SIDE|NIGHT|FULL)/' => 'wiko',
        '/ARK/' => 'ark',
        '/(ever(glory|shine|miracle|mellow|classic|trendy|fancy|vivid|slim|glow|magic|smart|star)[^\/;]*) build|e70[25]0hd|e7914hg|e8050h[dg]|e8051hd|e9054hd/i' => 'evertek', // must be before Magic
        '/Magic/' => 'magic',
        '/M[Ii][ -]([0-9]|PAD|M[AI]X|NOTE|A1|1S|3|ONE)/' => 'xiaomi',
        '/HM[ _](NOTE|1SC|1SW|1S|1)/' => 'xiaomi',
        '/WeTab/' => 'neofonie',
        '/SIE\-/' => 'siemens',
        '/CAL21|C771|C811/' => 'casio',
        '/g3mini/i' => 'lg',
        '/OK[AU][0-9]{1,2}/' => 'ouki',
        '/numy|novo[0-9]/i' => 'ainol',
        '/[AC][0-9]{5}/' => 'nomi',
        '/one e[0-9]{4}/i' => 'oneplus',
        '/one a200[135]/i' => 'oneplus',
        '/TBD[0-9]{4}|TBD[BCG][0-9]{3,4}/' => 'zeki',
        '/ac0731b|ac0732c|ac1024c|ac7803c|bc9710am|el72b|er71b|lc0720c|lc0723b|lc0725b|lc0804b|lc0808b|lc0809b|lc0810c|lc0816c|lc0901d|lc1016c|mt0724b|mt0729b|mt0729d|mt0739d|mt0811b|mt0812e|mt7801c|oc1020a|qs9719d|qs9718c|qs9715f|qs1023h|qs0815c|qs0730c|qs0728c|qs0717d|qs0716d|qs0715c|rc0709b|rc0710b|rc0718c|rc0719h|rc0721b|rc0722c|rc0726b|rc0734h|rc0743h|rc0813c|rc0817c|rc1018c|rc1019g|rc1025f|rc1301c|rc7802f|rc9711b|rc9712c|rc9716b|rc9717b|rc9724c|rc9726c|rc9727f|rc9730c|rc9731c|ts0807b|ts1013b|vm0711a|vm1017a/i' => '3q',
        '/ImPAD6213M_v2/' => 'impression',
        '/D6000/' => 'innos',
        '/[SV]T[0-9]{5}/' => 'trekstor',
        '/e6560|c6750|c6742|c6730|c6522n|c5215|c5170|c5155|c5120|dm015k|kc\-s701/i' => 'kyocera',
        '/p4501|p850x|e4004|e691x|p1050x|p1032x|p1040x|s1035x|p1035x|p4502|p851x|x5001/i' => 'medion',
        '/g6600/i' => 'huawei',
        '/DG[0-9]{3,4}/' => 'doogee',
        '/Touchlet|X7G|X10\./' => 'pearl',
        '/terra pad|pad1002/i' => 'wortmann',
        '/g710[68]/i' => 'samsung',
        '/e1041x|e1050x|b5531/i' => 'lenovo',
        '/rio r1|gsmart/i' => 'gigabyte',
        '/[ ;](l\-?ement|l\-ite|l\-?ixir)|e[89]12|e731|e1031|kt712a_4\\.4|tab1062|tab950/i' => 'logicom',
        '/ [CDEFG][0-9]{4}/' => 'sony',
        '/PM\-[0-9]{4}/' => 'sanyo',
        '/AT1010\-T/' => 'lenovo',
        '/folio_and_a|toshiba_ac_and_az|folio100|at1s0|at[0-9]{2,3}|t\-0[0-9][cd]/i' => 'toshiba',
        '/(aqua|cloud)[_ \.]/i' => 'intex',
        '/bv[5-8]000/i' => 'blackview',
        '/XELIO_NEXT|(MAVEN|SPACE|TAO|THOR)_?X?10/' => 'odys',
        '/NEXT|Next[0-9]|DATAM803HC|NX785QC8G|NXM900MC|NX008HD8G|NX010HI8G|NXM908HC|NXM726/' => 'nextbook',
        '/A310|ATLAS[_ ]W|BASE Tab|KIS PLUS|N799D|N9101|N9180|N9510|N9515|N9520|N9521|N9810|N918St|N958St|NX[0-9]{2,3}|OPEN[C2]|U9180| V9 |V788D|V8000|V9180|X501|X920|Z221|Z835|Z768G|Z820|Z981/' => 'zte',
        '/lutea|bs 451|n9132|grand s flex|e8q\+|s8q|s7q/i' => 'zte',
        '/ultrafone/i' => 'zen',
        '/ mt791 /i' => 'keenhigh',
        '/g100w|stream\-s110| (a1|a3|b1|b3)\-/i' => 'acer',
        '/b51\+/i' => 'sprd',
        '/sphs_on_hsdroid/i' => 'mhorse',
        '/TAB A742|TAB7iD|TAB 10Q|ZEN [0-9]/' => 'wexler',
        '/VS810PP/' => 'lg',
        '/A400/' => 'celkon',
        '/A5000/' => 'sony',
        '/A1002|A811|S[45]A[0-9]|SC7 PRO HD/' => 'lexand',
        '/A121|A120|A116|A114|A093|A065| A96 |Q327| A47/' => 'micromax',
        '/smart ?tab|s6000d/i' => 'lenovo',
        '/S208|S308|S550|S600|Z100 Pro|NOTE Plus/' => 'cubot',
        '/q8002/i' => 'crypto',
        '/a1000s|q10[01]0i?|q[678]00s?|q2000|omega[ _][0-9]/i' => 'xolo',
        '/s750/i' => 'beneve',
        '/blade/i' => 'zte',
        '/ z110/i' => 'xido',
        '/karbonn|titanium|machfive|sparkle v|s109/i' => 'karbonn', // must be before acer
        '/a727/i' => 'azpen',
        '/(ags|a[ln]e|ath|ba[ch]|bl[an]|bnd|cam|ch[cm]|che[12]?|clt|dli|duk|eml|fig|frd|gra|h[36]0|kiw|lon|m[hy]a|nem|plk|pra|rne|scl|trt|vky|vtr|was|y220)\-/i' => 'huawei',
        '/V1[0-9]{2}|GN[0-9]{3}/' => 'gionee',
        '/v919 3g air/i' => 'onda', // must be before acer
        '/a501 bright/i' => 'bravis', // must be before acer
        '/tm785m3/i' => 'nuvision',
        '/m785|800p71d|800p3[12]c|101p51c|x1010|a1013r|s10\-0g/i' => 'mecer', // must be before acer
        '/ [aevzs][0-9]{3} /i' => 'acer',
        '/AT\-AS[0-9]{2}[DS]/' => 'wolfgang',
        '/vk\-/i' => 'vkmobile',
        '/FP[12]/' => 'fairphone',
        '/le 1 pro|le 2|le max|le ?x[0-9]{3}/i' => 'leeco',
        '/loox|uno_x10|xelio|neo_quad10|ieos_quad|sky plus|maven_10_plus|maven10_hd_plus_3g|maven_x10_hd_lte|space10_plus|adm816|noon|xpress|genesis|tablet-pc-4|kinder-tablet|evolution12|mira|score_plus|pro q8 plus|rapid7lte|neo6_lte|rapid_10/i' => 'odys',
        '/BARRY|BIRDY|BLOOM|CINK|FEVER|FIZZ|HARRY|GETAWAY| GOA|HIGHWAY|IGGY|JIMMY|JERRY|KITE|OZZY|PLUS|PULP|RIDGE|ROBBY|SLIDE|STAIRWAY|SUBLIM|SUNNY|SUNSET|U FEEL|WAX/' => 'wiko',
        '/l5510|lenny|rainbow|view xl|view prime|view build|w_k[46]00/i' => 'wiko',
        '/aquaris|bq [^\/;]+ build|bqs-400[57]| m10 |edison 3/i' => 'bq',
        '/QtCarBrowser/' => 'teslamotors',
        '/m[bez][0-9]{3}/i' => 'motorola',
        '/s5003d_champ/i' => 'switel',
        '/xperia/i' => 'sony',
        '/momodesign md droid/i' => 'zte',
        '/ droid|milestone|xoom|razr hd| z /i' => 'motorola',
        '/(vns)\-/i' => 'huawei',
        '/SGP[0-9]{3}|X[ML][0-9]{2}[th]/' => 'sony',
        '/sgpt[0-9]{2}/i' => 'sony',
        '/(YU|AO)[0-9]{4}/' => 'yu',
        '/u[0-9]{4}|ideos|vodafone[ _]858|vodafone 845|ascend|m860| p6 |hi6210sft|honor|enjoy 7 plus/i' => 'huawei',
        '/vodafone 890n/i' => 'yulong',
        '/OP[0-9]{3}/' => 'olivetti',
        '/VS[0-9]{3}/' => 'lg',
        '/surftab|vt10416|breeze 10\.1 quad|xintroni10\.1|st70408_4/i' => 'trekstor',
        '/mt6515m\-a1\+/i' => 'united',
        '/ c7 | h1 | cheetah | x12 | x16 | x17_s | x18 /i' => 'cubot',
        '/mt10b/i' => 'excelvan',
        '/mt10/i' => 'mtn',
        '/m1009|mt13|kp\-703/i' => 'excelvan',
        '/MT6582\/|mn84l_8039_20203/' => 'unknown',
        '/mt6515m\-a1\+/' => 'united',
        '/BIGCOOL|COOLFIVE|COOL\-K|Just5|LINK5/' => 'konrow',
        '/v1_viper|a4you|p5_quad|x2_soul|ax4nano|x1_soul|p5_energy/i' => 'allview',
        '/PLT([^;\/]+) Build/' => 'proscan',
        '/[SLWM]T[0-9]{2}|[SM]K[0-9]{2}|SO\-[0-9]{2}[BCDEFG]/' => 'sony',
        '/l[0-9]{2}u/i' => 'sony',
        '/RMD\-[0-9]{3,4}/' => 'ritmix',
        '/AX[0-9]{3}/' => 'bmobile',
        '/free(way )?tab|xino z[0-9]+ x[0-9]+/i' => 'modecom',
        '/FX2/' => 'faktorzwei',
        '/AN[0-9]{1,2}|ARCHM[0-9]{3}/' => 'arnova',
        '/POV|TAB\-PROTAB|MOB\-5045/' => 'point-of-view',
        '/PI[0-9]{4}/' => 'philips',
        '/FUNC/' => 'dfunc',
        '/GM/' => 'generalmobile',
        '/ZP[0-9]{3}/' => 'zopo',
        '/s450[0-9]/i' => 'dns',
        '/vtab1008/i' => 'vizio',
        '/tab(07|10)\-[0-9]{3}|(luna|noble|xenta)[ \-]tab[0-9]/i' => 'yarvik',
        '/venue|xcd35/i' => 'dell',
        '/funtab|zilo/i' => 'orange',
        '/fws610_eu/i' => 'phicomm',
        '/samurai10/i' => 'shiru',
        '/ignis 8/i' => 'tbtouch',
        '/k1 turbo/i' => 'kingzone',
        '/ a10 |mp907c/i' => 'allwinner',
        '/shield tablet/i' => 'nvidia',
        '/u7 plus|u16 max|k6000 pro|k6000 plus|k4000|k10000|universetap/i' => 'oukitel',
        '/k107/i' => 'yuntab',
        '/mb40ii1/i' => 'dns',
        '/m3 note/i' => 'meizu',
        '/ m[35] |f103| e7 | v6l |pioneer|dream_d1|(adr|android) 4\.2.* p2/i' => 'gionee',
        '/w[12]00| w8/i' => 'thl',
        '/w713|cp[0-9]{4}|n930|5860s|8079|8190q|8295/i' => 'coolpad',
        '/w960/i' => 'sony',
        '/n8000d|n[579]1[01]0/i' => 'samsung',
        '/n003/i' => 'neo',
        '/ v1 /i' => 'maxtron',
        '/7007hd/i' => 'perfeo',
        '/TM\-[0-9]{4}/' => 'texet',
        '/ W[0-9]{3}[ )]/' => 'haier',
        '/NT\-[0-9]{4}[SPTM]/' => 'iconbit',
        '/T[GXZ][0-9]{2,3}/' => 'irbis',
        '/YD[0-9]{3}/' => 'yota',
        '/OK[0-9]{3}/' => 'sunup',
        '/ACE/' => 'samsung',
        '/PX\-[0-9]{4}/' => 'intego',
        '/ip[0-9]{4}/i' => 'dex',
        '/P1060X/' => 'lenovo',
        '/element p501|element[ _]?(7|8|9\.7|10)/i' => 'sencor',
        '/elegance|intelect|cavion|slim ?tab ?(7|8|10)|core 10\.1 dual 3g/i' => 'kiano',
        '/ c4 |phablet [0-9]|tab[_ ]?(7|8|9|10)[_ ]?3g/i' => 'trevi',
        '/ v[0-9]\-?[ace]?[ )]/i' => 'inew',
        '/(RP|KM)\-U[DQ]M[0-9]{2}/' => 'verico',
        '/KM\-/' => 'kttech',
        '/primo76|primo 91/i' => 'msi',
        '/x\-pad|navipad/i' => 'texet', // must be before odys' visio rule
        '/visio/i' => 'odys',
        '/ g3 |p713|p509|c660|(ls|vm|ln)[0-9]{3}|optimus g|l\-0[0-9][cde]/i' => 'lg',
        '/zera[ _]f|boost iise|ice2|prime s|explosion/i' => 'highscreen',
        '/iris708/i' => 'ais',
        '/l930/i' => 'ciotcud',
        '/x8\+/i' => 'triray',
        '/pmsmart450/i' => 'pmedia',
        '/f031|n900\+|sc[lt]2[0-9]|isw11sc|s7562|sghi[0-9]{3}|i8910/i' => 'samsung',
        '/iusai/i' => 'opsson',
        '/netbox| x10 | e1[05]i| x2 |r800x|s500i|x1i|x10i|[ls]39h|h3113|h3213|h3311|h4113|h8216|h8266|h8314|h8324|ebrd[0-9]{4}/i' => 'sony',
        '/PROV?[0-9]{3}[B0-9]?/' => 'polaroid',
        '/x90[0-9]{1,2}|n52[0-9]{2}|r[12678][0-9]{2,3}|u70[0-9]t|find7|a3[37]f|r7[ks]?f|r7plusf| 1201 |n1t|cph1609/i' => 'oppo',
        '/(mpqc|mpdc)[0-9]{1,4}|ph[0-9]{3}|mid(7c|74c|82c|84c|801|811|701|711|170|77c|43c|102c|103c|104c|114c)|mp(843|717|718|843|888|959|969|1010|7007|7008)|mgp7/i' => 'mpman',
        '/N[0-9]{4}/' => 'star',
        '/technipad|aqipad|techniphone/i' => 'technisat', // must be before apple
        '/medipad/i' => 'bewatec',
        '/mipad/i' => 'xiaomi',
        '/android.*iphone/i' => 'xianghe',
        '/ucweb.*adr.*iphone/i' => 'xianghe',
        '/ipodder|tripadvisor/i' => 'generalmobile',
        '/ipad|ipod|iphone|like mac os x|darwin|cfnetwork|dataaccessd|iuc ?\(/i' => 'apple',
        '/iPh[0-9]\,[0-9]|Puffin\/[0-9\.]+I[TP]/' => 'apple',
        '/vf\-?[0-9]{3,4}/i' => 'tcl',
        '/One|E[vV][oO] ?3D|PJ83100|831C|Eris 2\.1|0PCV1|MDA|0PJA10|P[CG][0-9]{5}/' => 'htc',
        '/vodafone 975/i' => 'vodafone',
        '/one [sx]|a315c|vpa|adr[0-9]{4}|wildfire|desire/i' => 'htc',
        '/t\-mobile/i' => 'tmobile',
        '/A101|A500|Z[25]00| T0[346789] | S55 |DA220HQL| E39 /' => 'acer',
        '/a1303|a309w/i' => 'china-phone',
        '/k910l| [ak]1 ?| a6[05] |yoga tablet|tab2a7\-|p770|zuk |(adr|android) [67].* p2|yb1\-x90l|b5060|s1032x|x1030x/i' => 'lenovo',
        '/impad/i' => 'impression',
        '/tab917qc|tab785dual/i' => 'sunstech',
        '/m7t|p93g|i75|m83g| m6 |m[69]pro| t9 /i' => 'pipo',
        '/md948g/i' => 'mway',
        '/smartphone650/i' => 'master',
        '/mx enjoy tv box/i' => 'geniatech',
        '/m5301/i' => 'iru',
        '/gv7777/i' => 'prestigio',
        '/9930i/i' => 'star',
        '/m717r\-hd/i' => 'vastking',
        '/m502/i' => 'navon',
        '/lencm900hz/i' => 'lenco',
        '/xm[13]00/i' => 'landvo',
        '/m370i/i' => 'infocus',
        '/dm550/i' => 'blackview',
        '/ m8 /i' => 'amlogic',
        '/m601/i' => 'aoc',
        '/IM\-[AT][0-9]{3}[LKS]|ADR910L/' => 'pantech',
        '/SPX\-[0-9]/' => 'simvalley',
        '/H[MTW]\-[GINW][0-9]{2,3}/' => 'haier',
        '/RG[0-9]{3}/' => 'ruggear',
        '/(android|adr).* iris/i' => 'lava',
        '/ap\-105/i' => 'mitashi',
        '/AP\-[0-9]{3}/' => 'assistant',
        '/ARM; WIN (JR|HD)/' => 'blu',
        '/tp[0-9]{1,2}(\.[0-9])?\-[0-9]{4}|tu\-[0-9]{4}/i' => 'ionik',
        '/ft[ _][0-9]{4}/i' => 'lifeware',
        '/(od|sm|yq)[0-9]{3}/i' => 'smartisan',
        '/ls\-[0-9]{4}/i' => 'lyf',
        '/redmi|note 4|2015562|2014(81[138]|501|011)|2013(02[23]|061)/i' => 'xiaomi',
        '/mx[0-9]/i' => 'meizu',
        '/x[69]pro|x5max_pro/i' => 'doogee',
        '/x[0-9] ?(plus|max|pro)/i' => 'vivo',
        '/neffos|tp[0-9]{3}/i' => 'tplink',
        '/tb[0-9]{3,4}/i' => 'acme',
        '/nt\. ?(p|i)10g2/i' => 'ninetec',
        '/N[BP][0-9]{2,3}/' => 'bravis',
        '/tp[0-9]{2}\-3g/i' => 'theq',
        '/ftj?[0-9]{3}/i' => 'freetel',
        '/RUNE/' => 'bsmobile',
        '/IRON/' => 'umi',
        '/tlink|every35|primo[78]|qm73[45]\-8g/i' => 'thomson',
        '/mz\-| m[0-9]s? |m[0-9]{3}|m[0-9] note|pro 5/i' => 'meizu',
        '/[sxz][0-9]{3}[ae]/i' => 'htc',
        '/(i\-style|iq) ?[0-9]/i' => 'imobile',
        '/pt\-gf200/i' => 'pantech',
        '/k\-8s/i' => 'keener',
        '/h1\+/i' => 'hummer',
        '/impress_l/i' => 'vertex',
        '/neo\-x5/i' => 'minix',
        '/tab\-97e\-01/i' => 'reellex',
        '/vega/i' => 'advent',
        '/dream| x9 |amaze|butterfly2/i' => 'htc',
        '/ xst2 /i' => 'fourgsystems',
        '/f10x/i' => 'nextway',
        '/adtab 7 lite/i' => 'adspec',
        '/neon\-n1|wing\-w2/i' => 'axgio',
        '/t118|t108/i' => 'twinovo',
        '/touareg8_3g/i' => 'accent',
        '/chagall/i' => 'pegatron',
        '/turbo x6/i' => 'turbopad',
        '/ l52 | g30 |pad g781/i' => 'haier',
        '/air a70/i' => 'roverpad',
        '/sp\-6020 quasar/i' => 'woo',
        '/q10s/i' => 'wopad',
        '/uq785\-m1bgv/i' => 'verico',
        '/t9666\-1/i' => 'telsda',
        '/h7100/i' => 'feiteng',
        '/xda|cocoon/i' => 'o2',
        '/kkt20|pixelv1|pixel v2\+| x17 |x1 atom|x1 selfie|x5 4g/i' => 'lava',
        '/pulse|mytouch4g|ameo|garminfone/i' => 'tmobile',
        '/g009/i' => 'yxtel',
        '/picopad_s1/i' => 'axioo',
        '/adi_5s/i' => 'artel',
        '/norma 2/i' => 'keneksi',
        '/t880g/i' => 'etuline',
        '/studio 5\.5|studio xl 2/i' => 'blu',
        '/f3_pro|y6_piano|y6 max| t6 /i' => 'doogee',
        '/tab\-970/i' => 'prology',
        '/a66a/i' => 'evercoss',
        '/n90fhdrk|n90 dual core2|n101 dual core2/i' => 'yuandao',
        '/nova/i' => 'catsound',
        '/i545/i' => 'samsung',
        '/discovery/i' => 'generalmobile',
        '/ct(10[0123]0|7[12]0|820)(w|fr)?[);\/ ]/i' => 'carrefour',
        '/t720/i' => 'motorola',
        '/n820|a862w/i' => 'amoi',
        '/tpc\-/i' => 'jaytech',
        '/ g9 /i' => 'mastone',
        '/dl1|eluga_arc_2/i' => 'panasonic',
        '/zt180/i' => 'zenithink',
        '/e1107/i' => 'yusu',
        '/is05/i' => 'sharp',
        '/p4d sirius/i' => 'nvsbl',
        '/ c2 /i' => 'zopo',
        '/a0001/i' => 'oneplus',
        '/smartpad/i' => 'einsundeins',
        '/i4901/i' => 'idea',
        '/lead [12]|t1_plus|elite [45]|shark 1|s8_pro/i' => 'leagoo',
        '/forward|dynamic/i' => 'ngm',
        '/gnet/i' => 'gnet',
        '/hive v 3g|hive iv 3g/i' => 'turbo-x',
        '/turkcell/i' => 'turkcell',
        '/is04/i' => 'kddi',
        '/be pro|paris|vienna|u007|future|power_3/i' => 'ulefone',
        '/t1x plus|vandroid/i' => 'advan',
        '/sense golly/i' => 'ipro',
        '/sirius_qs/i' => 'vonino',
        '/dl 1803/i' => 'dl',
        '/s10q\-3g/i' => 'smartbook',
        '/ s30 /i' => 'firefly',
        '/apollo|thor|mars pro/i' => 'vernee',
        '/1505\-a02|inote/i' => 'itel',
        '/mi(tab|smart)/i' => 'wolder',
        '/pixel|glass 1/i' => 'google',
        '/909t| m13 /i' => 'mpie',
        '/z30/i' => 'magnus',
        '/up580|up520/i' => 'uhappy',
        '/swift/i' => 'wileyfox',
        '/m9c max/i' => 'bqeel',
        '/qt\-10/i' => 'qmax',
        '/ilium l820/i' => 'lanix',
        '/s501m 3g|t700i_3g/i' => 'fourgood',
        '/ixion_es255|h135/i' => 'dexp',
        '/atl\-21/i' => 'artizlee',
        '/w032i\-c3|tr10rs1|tr10cd1/i' => 'intel',
        '/CS[0-9]{2}/' => 'cyrus',
        '/ t02 /i' => 'changhong',
        '/crown| r6 | a8 |alife [ps]1|omega_pro/i' => 'blackview',
        '/london|hammer_s|z2 pro|plus e/i' => 'umi',
        '/vi8 plus|hibook|hi10 pro/i' => 'chuwi',
        '/jy\-/i' => 'jiayu',
        '/ m20 /i' => 'timmy',
        '/g708 oc/i' => 'colorfly',
        '/q880_xk/i' => 'tianji',
        '/c55/i' => 'ctroniq',
        '/l900/i' => 'landvo',
        '/ k5 /i' => 'komu',
        '/ x6 /i' => 'voto',
        '/VT[0-9]{3}/' => 'voto',
        '/ m71 /i' => 'eplutus',
        '/ (d10|y14) /i' => 'xgody',
        '/tab1024/i' => 'intenso',
        '/ifive mini 4s/i' => 'fnf',
        '/ i10 | h150 /i' => 'symphony',
        '/ arc /i' => 'kobo',
        '/m92d\-3g/i' => 'sumvier',
        '/ f5 | h7 /i' => 'tecno',
        '/a88x/i' => 'alldaymall',
        '/bs1078/i' => 'yonestoptech',
        '/excellent8/i' => 'tomtec',
        '/ih\-g101/i' => 'innohit',
        '/g900/i' => 'ippo',
        '/nimbus 80qb/i' => 'woxter',
        '/vkb011b/i' => 'fengxiang',
        '/trooper|tornado|thunder/i' => 'kazam',
        '/ n3 /i' => 'goophone',
        '/king 7/i' => 'pptv',
        '/(admire[_ ][^\/;]+|cinemax[^\/;)]+)(build|\) u)/i' => 'zen',
        '/1501_m02/i' => 'threesixty',
        '/d4c5|k9c6|c5j6/i' => 'teclast',
        '/t72/i' => 'oysters',
        '/ns\-14t004|ns\-p10a6100/i' => 'insignia',
        '/blaster 2/i' => 'justfive',
        '/picasso/i' => 'bluboo',
        '/strongphoneq4/i' => 'evolveo',
        '/shift[457]/i' => 'shift',
        '/k960/i' => 'jlinksz',
        '/i\-call|elektra l|neon[79]/i' => 'ijoy',
        '/ektra/i' => 'kodak',
        '/kt107/i' => 'bdf',
        '/m52_red_note/i' => 'mlais',
        '/sunmicrosystems/i' => 'sun',
        '/ a50/i' => 'micromax',
        '/max2_plus_3g/i' => 'innjoo',
        '/coolpix s800c/i' => 'nikon',
        '/vsd220/i' => 'viewsonic',
        '/primo[\- _]|walpad/i' => 'walton',
        '/x538/i' => 'sunsbell',
        '/sf1/i' => 'obi',
        '/harrier tab/i' => 'ee',
        '/excite prime/i' => 'cloudfone',
        '/ z1 /i' => 'ninetology',
        '/ Presto /' => 'oplus',
        '/crono/i' => 'majestic',
        '/NS[0-9]{1,4}/' => 'nous',
        '/monster x5|quadra 7 ultraslim/i' => 'pentagram',
        '/F1[0-9]/' => 'pulid',
        '/q\-smart|qtab/i' => 'qmobile',
        '/andromax|androtab|pd6d1j/i' => 'smartfren',
        '/ax5_duo/i' => 'maxx',
        '/ga10h/i' => 'gooweel',
        '/ypy_s450/i' => 'positivo',
        '/ph\-1/i' => 'essential',
        '/tc970/i' => 'lepan',
        '/mfc[0-9]{3}[a-z]{2,}/i' => 'lexibook',
        '/vt75c/i' => 'videocon',
        '/rct[0-9]{4}/i' => 'rca-tablets',
        '/(centurion|gladiator| glory|luxury|sensuelle|victory)([ _\-]?[2-6])?[);\/ ]|surfing tab/i' => 'brondi',
        '/momo([0-9]|mini)/i' => 'ployer',
        '/ezee/i' => 'storex',
        '/cyclone [^\/;]+ build/i' => 'sumvision',
        '/ctc[0-9]{3}/i' => 'ctc',
        '/I5/' => 'sop',
        '/i5/' => 'vsun',
        '/KIN\.(One|Two)|ZuneHD|Windows NT 6\.(2|3).*ARM;/' => 'microsoft',
    ];

    /**
     * @var \BrowserDetector\Loader\DeviceLoaderFactory
     */
    private $loaderFactory;

    /**
     * @param \BrowserDetector\Cache\CacheInterface $cache
     * @param \Psr\Log\LoggerInterface              $logger
     */
    public function __construct(CacheInterface $cache, LoggerInterface $logger)
    {
        $this->loaderFactory = new DeviceLoaderFactory($cache, $logger);
    }

    /**
     * detects the device name from the given user agent
     *
     * @param string $useragent
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \BrowserDetector\Loader\NotFoundException
     *
     * @return array
     */
    public function __invoke(string $useragent): array
    {
        $loaderFactory = $this->loaderFactory;

        foreach ($this->factories as $rule => $company) {
            try {
                if (preg_match($rule, $useragent)) {
                    $loader = $loaderFactory($company, 'mobile');

                    return $loader($useragent);
                }
            } catch (\Throwable $e) {
                throw new \InvalidArgumentException(sprintf('An error occured while matching rule "%s"', $rule), 0, $e);
            }
        }

        $loader = $loaderFactory('unknown', 'mobile');

        return $loader($useragent);
    }
}
