//
//var vsMapColor = '#7F31AD';
//var ncMapColor = '#306DF2';
//var trMapColor = 'rgba(218, 0, 0, 1)';
//var nsMapColor = '#fff';



var socket_example_message = {"data": {"resultID": 31, "timestamp": "1424633820", "facilityID": "233000", "facilityOwner": "2", "facilityOldOwner": "1", "controlVS": "43", "controlNC": "46", "controlTR": "10", "durationHeld": "546", "defence": 0, "zone": "8", "world": "19", "outfitCaptured": "0"}, "messageType": "facility"};

var map_data = map_data_indar;
var ps2_territory_layer = null;
var ps2_marker_layer = L.layerGroup();
var ps2_map_active_tiles = null;

var map_state = {};
var map = null;
var map_control = null;



if (!eventZone) {
    var eventZone = 2;
}

//if (!mapStats) {
//    var mapStats = {};
////    Convert map region states to quick lookup map
//    for (i in map_state_indar.map_list[0].Regions.Row) {
//        region = map_state_indar.map_list[0].Regions.Row[i].RowData;
//        mapStats[FacDataInverse[region.RegionId].facilityID] = {"facilityOwner":  region.FactionId};
//    }
//}

//var mapStats = {"230000":{"id":205367,"world":"19","zone":"8","facilityID":"230000","facilityTypeID":"6","facilityOwner":"1"},"231000":{"id":205368,"world":"19","zone":"8","facilityID":"231000","facilityTypeID":"6","facilityOwner":"1"},"232000":{"id":205369,"world":"19","zone":"8","facilityID":"232000","facilityTypeID":"6","facilityOwner":"1"},"233000":{"id":205370,"world":"19","zone":"8","facilityID":"233000","facilityTypeID":"6","facilityOwner":"1"},"234000":{"id":205371,"world":"19","zone":"8","facilityID":"234000","facilityTypeID":"6","facilityOwner":"1"},"235000":{"id":205372,"world":"19","zone":"8","facilityID":"235000","facilityTypeID":"6","facilityOwner":"1"},"236000":{"id":205373,"world":"19","zone":"8","facilityID":"236000","facilityTypeID":"6","facilityOwner":"1"},"237000":{"id":205374,"world":"19","zone":"8","facilityID":"237000","facilityTypeID":"6","facilityOwner":"1"},"238000":{"id":205387,"world":"19","zone":"8","facilityID":"238000","facilityTypeID":"6","facilityOwner":"1"},"239000":{"id":205376,"world":"19","zone":"8","facilityID":"239000","facilityTypeID":"6","facilityOwner":"1"},"240000":{"id":205377,"world":"19","zone":"8","facilityID":"240000","facilityTypeID":"6","facilityOwner":"1"},"241000":{"id":205378,"world":"19","zone":"8","facilityID":"241000","facilityTypeID":"6","facilityOwner":"1"},"242000":{"id":205379,"world":"19","zone":"8","facilityID":"242000","facilityTypeID":"6","facilityOwner":"1"},"243000":{"id":205380,"world":"19","zone":"8","facilityID":"243000","facilityTypeID":"6","facilityOwner":"1"},"244000":{"id":205381,"world":"19","zone":"8","facilityID":"244000","facilityTypeID":"6","facilityOwner":"1"},"244100":{"id":205398,"world":"19","zone":"8","facilityID":"244100","facilityTypeID":"6","facilityOwner":"1"},"244200":{"id":205399,"world":"19","zone":"8","facilityID":"244200","facilityTypeID":"6","facilityOwner":"1"},"244300":{"id":205400,"world":"19","zone":"8","facilityID":"244300","facilityTypeID":"6","facilityOwner":"1"},"244500":{"id":205402,"world":"19","zone":"8","facilityID":"244500","facilityTypeID":"6","facilityOwner":"2"},"244600":{"id":205403,"world":"19","zone":"8","facilityID":"244600","facilityTypeID":"6","facilityOwner":"1"},"244610":{"id":205426,"world":"19","zone":"8","facilityID":"244610","facilityTypeID":"6","facilityOwner":"1"},"244620":{"id":205427,"world":"19","zone":"8","facilityID":"244620","facilityTypeID":"6","facilityOwner":"1"},"245000":{"id":205382,"world":"19","zone":"8","facilityID":"245000","facilityTypeID":"5","facilityOwner":"1"},"246000":{"id":205383,"world":"19","zone":"8","facilityID":"246000","facilityTypeID":"5","facilityOwner":"1"},"247000":{"id":205384,"world":"19","zone":"8","facilityID":"247000","facilityTypeID":"5","facilityOwner":"1"},"248000":{"id":205385,"world":"19","zone":"8","facilityID":"248000","facilityTypeID":"5","facilityOwner":"1"},"249000":{"id":205386,"world":"19","zone":"8","facilityID":"249000","facilityTypeID":"5","facilityOwner":"1"},"250000":{"id":205375,"world":"19","zone":"8","facilityID":"250000","facilityTypeID":"5","facilityOwner":"2"},"251000":{"id":205388,"world":"19","zone":"8","facilityID":"251000","facilityTypeID":"3","facilityOwner":"2"},"251010":{"id":205405,"world":"19","zone":"8","facilityID":"251010","facilityTypeID":"6","facilityOwner":"1"},"251020":{"id":205406,"world":"19","zone":"8","facilityID":"251020","facilityTypeID":"6","facilityOwner":"1"},"251030":{"id":205407,"world":"19","zone":"8","facilityID":"251030","facilityTypeID":"6","facilityOwner":"2"},"252000":{"id":205389,"world":"19","zone":"8","facilityID":"252000","facilityTypeID":"2","facilityOwner":"1"},"252010":{"id":205408,"world":"19","zone":"8","facilityID":"252010","facilityTypeID":"6","facilityOwner":"1"},"252020":{"id":205409,"world":"19","zone":"8","facilityID":"252020","facilityTypeID":"6","facilityOwner":"1"},"253000":{"id":205390,"world":"19","zone":"8","facilityID":"253000","facilityTypeID":"2","facilityOwner":"1"},"253010":{"id":205410,"world":"19","zone":"8","facilityID":"253010","facilityTypeID":"6","facilityOwner":"1"},"253020":{"id":205411,"world":"19","zone":"8","facilityID":"253020","facilityTypeID":"6","facilityOwner":"1"},"253030":{"id":205412,"world":"19","zone":"8","facilityID":"253030","facilityTypeID":"6","facilityOwner":"1"},"253040":{"id":205413,"world":"19","zone":"8","facilityID":"253040","facilityTypeID":"6","facilityOwner":"1"},"254000":{"id":205391,"world":"19","zone":"8","facilityID":"254000","facilityTypeID":"4","facilityOwner":"1"},"254010":{"id":205414,"world":"19","zone":"8","facilityID":"254010","facilityTypeID":"6","facilityOwner":"1"},"254020":{"id":205415,"world":"19","zone":"8","facilityID":"254020","facilityTypeID":"6","facilityOwner":"1"},"254030":{"id":205416,"world":"19","zone":"8","facilityID":"254030","facilityTypeID":"6","facilityOwner":"1"},"255000":{"id":205392,"world":"19","zone":"8","facilityID":"255000","facilityTypeID":"3","facilityOwner":"1"},"255010":{"id":205417,"world":"19","zone":"8","facilityID":"255010","facilityTypeID":"6","facilityOwner":"1"},"255020":{"id":205418,"world":"19","zone":"8","facilityID":"255020","facilityTypeID":"6","facilityOwner":"1"},"255030":{"id":205419,"world":"19","zone":"8","facilityID":"255030","facilityTypeID":"6","facilityOwner":"1"},"256000":{"id":205393,"world":"19","zone":"8","facilityID":"256000","facilityTypeID":"2","facilityOwner":"1"},"256010":{"id":205420,"world":"19","zone":"8","facilityID":"256010","facilityTypeID":"6","facilityOwner":"1"},"256020":{"id":205421,"world":"19","zone":"8","facilityID":"256020","facilityTypeID":"6","facilityOwner":"1"},"256030":{"id":205422,"world":"19","zone":"8","facilityID":"256030","facilityTypeID":"6","facilityOwner":"1"},"257000":{"id":205394,"world":"19","zone":"8","facilityID":"257000","facilityTypeID":"3","facilityOwner":"1"},"257010":{"id":205423,"world":"19","zone":"8","facilityID":"257010","facilityTypeID":"6","facilityOwner":"1"},"257020":{"id":205424,"world":"19","zone":"8","facilityID":"257020","facilityTypeID":"6","facilityOwner":"1"},"257030":{"id":205425,"world":"19","zone":"8","facilityID":"257030","facilityTypeID":"6","facilityOwner":"1"},"258000":{"id":205395,"world":"19","zone":"8","facilityID":"258000","facilityTypeID":"7","facilityOwner":"3"},"259000":{"id":205396,"world":"19","zone":"8","facilityID":"259000","facilityTypeID":"7","facilityOwner":"2"},"260000":{"id":205397,"world":"19","zone":"8","facilityID":"260000","facilityTypeID":"7","facilityOwner":"1"},"260010":{"id":205404,"world":"19","zone":"8","facilityID":"260010","facilityTypeID":"5","facilityOwner":"1"},"310005":{"id":205401,"world":"19","zone":"8","facilityID":"310005","facilityTypeID":"6","facilityOwner":"1"}};
//FacDataInverse = {"2101":{"facilityID":7500,"facilityMapID":2101},"2102":{"facilityID":4401,"facilityMapID":2102},"2103":{"facilityID":4001,"facilityMapID":2103},"2104":{"facilityID":3801,"facilityMapID":2104},"2105":{"facilityID":3400,"facilityMapID":2105},"2106":{"facilityID":3601,"facilityMapID":2106},"2107":{"facilityID":3201,"facilityMapID":2107},"2108":{"facilityID":7000,"facilityMapID":2108},"2109":{"facilityID":118000,"facilityMapID":2109},"2201":{"facilityID":7801,"facilityMapID":2201},"2202":{"facilityID":120000,"facilityMapID":2202},"2203":{"facilityID":4801,"facilityMapID":2203},"2301":{"facilityID":5300,"facilityMapID":2301},"2302":{"facilityID":5500,"facilityMapID":2302},"2303":{"facilityID":5100,"facilityMapID":2303},"2304":{"facilityID":5200,"facilityMapID":2304},"2305":{"facilityID":5900,"facilityMapID":2305},"2306":{"facilityID":6200,"facilityMapID":2306},"2307":{"facilityID":6100,"facilityMapID":2307},"2308":{"facilityID":6000,"facilityMapID":2308},"2309":{"facilityID":5800,"facilityMapID":2309},"2310":{"facilityID":5700,"facilityMapID":2310},"2311":{"facilityID":6500,"facilityMapID":2311},"2312":{"facilityID":6400,"facilityMapID":2312},"2313":{"facilityID":6300,"facilityMapID":2313},"2402":{"facilityID":201,"facilityMapID":2402},"2403":{"facilityID":202,"facilityMapID":2403},"2404":{"facilityID":203,"facilityMapID":2404},"2405":{"facilityID":204,"facilityMapID":2405},"2406":{"facilityID":205,"facilityMapID":2406},"2407":{"facilityID":206,"facilityMapID":2407},"2408":{"facilityID":207,"facilityMapID":2408},"2409":{"facilityID":208,"facilityMapID":2409},"2410":{"facilityID":209,"facilityMapID":2410},"2411":{"facilityID":210,"facilityMapID":2411},"2412":{"facilityID":211,"facilityMapID":2412},"2413":{"facilityID":212,"facilityMapID":2413},"2414":{"facilityID":213,"facilityMapID":2414},"2415":{"facilityID":214,"facilityMapID":2415},"2416":{"facilityID":215,"facilityMapID":2416},"2417":{"facilityID":216,"facilityMapID":2417},"2418":{"facilityID":217,"facilityMapID":2418},"2419":{"facilityID":218,"facilityMapID":2419},"2420":{"facilityID":219,"facilityMapID":2420},"2421":{"facilityID":220,"facilityMapID":2421},"2422":{"facilityID":221,"facilityMapID":2422},"2423":{"facilityID":222,"facilityMapID":2423},"2424":{"facilityID":223,"facilityMapID":2424},"2425":{"facilityID":224,"facilityMapID":2425},"2426":{"facilityID":225,"facilityMapID":2426},"2427":{"facilityID":226,"facilityMapID":2427},"2428":{"facilityID":227,"facilityMapID":2428},"2429":{"facilityID":228,"facilityMapID":2429},"2430":{"facilityID":229,"facilityMapID":2430},"2431":{"facilityID":230,"facilityMapID":2431},"2432":{"facilityID":231,"facilityMapID":2432},"2433":{"facilityID":232,"facilityMapID":2433},"2436":{"facilityID":235,"facilityMapID":2436},"2437":{"facilityID":236,"facilityMapID":2437},"2438":{"facilityID":237,"facilityMapID":2438},"2440":{"facilityID":239,"facilityMapID":2440},"2442":{"facilityID":241,"facilityMapID":2442},"2443":{"facilityID":242,"facilityMapID":2443},"2444":{"facilityID":243,"facilityMapID":2444},"2447":{"facilityID":246,"facilityMapID":2447},"2448":{"facilityID":247,"facilityMapID":2448},"2449":{"facilityID":248,"facilityMapID":2449},"2451":{"facilityID":250,"facilityMapID":2451},"2453":{"facilityID":252,"facilityMapID":2453},"2454":{"facilityID":3410,"facilityMapID":2454},"2455":{"facilityID":3420,"facilityMapID":2455},"2456":{"facilityID":3430,"facilityMapID":2456},"2457":{"facilityID":4010,"facilityMapID":2457},"2458":{"facilityID":4020,"facilityMapID":2458},"2459":{"facilityID":4030,"facilityMapID":2459},"2460":{"facilityID":4430,"facilityMapID":2460},"2461":{"facilityID":4420,"facilityMapID":2461},"2462":{"facilityID":4410,"facilityMapID":2462},"2463":{"facilityID":7020,"facilityMapID":2463},"2464":{"facilityID":7030,"facilityMapID":2464},"2465":{"facilityID":7010,"facilityMapID":2465},"2466":{"facilityID":3620,"facilityMapID":2466},"2467":{"facilityID":3610,"facilityMapID":2467},"2468":{"facilityID":3630,"facilityMapID":2468},"2469":{"facilityID":118030,"facilityMapID":2469},"2470":{"facilityID":118010,"facilityMapID":2470},"2471":{"facilityID":118020,"facilityMapID":2471},"2472":{"facilityID":3810,"facilityMapID":2472},"2473":{"facilityID":3820,"facilityMapID":2473},"2474":{"facilityID":7520,"facilityMapID":2474},"2475":{"facilityID":7510,"facilityMapID":2475},"2476":{"facilityID":7530,"facilityMapID":2476},"2477":{"facilityID":3210,"facilityMapID":2477},"2478":{"facilityID":3230,"facilityMapID":2478},"2479":{"facilityID":3220,"facilityMapID":2479},"4102":{"facilityID":262000,"facilityMapID":4102},"4103":{"facilityID":263000,"facilityMapID":4103},"4104":{"facilityID":264000,"facilityMapID":4104},"4105":{"facilityID":265000,"facilityMapID":4105},"4107":{"facilityID":267000,"facilityMapID":4107},"4108":{"facilityID":268000,"facilityMapID":4108},"4109":{"facilityID":269000,"facilityMapID":4109},"4110":{"facilityID":270000,"facilityMapID":4110},"4111":{"facilityID":271000,"facilityMapID":4111},"4112":{"facilityID":272000,"facilityMapID":4112},"4113":{"facilityID":273000,"facilityMapID":4113},"4114":{"facilityID":274000,"facilityMapID":4114},"4115":{"facilityID":275000,"facilityMapID":4115},"4116":{"facilityID":276000,"facilityMapID":4116},"4117":{"facilityID":277000,"facilityMapID":4117},"4118":{"facilityID":278000,"facilityMapID":4118},"4120":{"facilityID":280000,"facilityMapID":4120},"4121":{"facilityID":281000,"facilityMapID":4121},"4122":{"facilityID":282000,"facilityMapID":4122},"4123":{"facilityID":283000,"facilityMapID":4123},"4124":{"facilityID":284000,"facilityMapID":4124},"4125":{"facilityID":285000,"facilityMapID":4125},"4126":{"facilityID":286000,"facilityMapID":4126},"4127":{"facilityID":287000,"facilityMapID":4127},"4130":{"facilityID":289000,"facilityMapID":4130},"4131":{"facilityID":290000,"facilityMapID":4131},"4132":{"facilityID":291000,"facilityMapID":4132},"4133":{"facilityID":292000,"facilityMapID":4133},"4134":{"facilityID":293000,"facilityMapID":4134},"4135":{"facilityID":294000,"facilityMapID":4135},"4136":{"facilityID":295000,"facilityMapID":4136},"4137":{"facilityID":296000,"facilityMapID":4137},"4138":{"facilityID":297000,"facilityMapID":4138},"4140":{"facilityID":299000,"facilityMapID":4140},"4141":{"facilityID":299010,"facilityMapID":4141},"4142":{"facilityID":299020,"facilityMapID":4142},"4143":{"facilityID":299030,"facilityMapID":4143},"4150":{"facilityID":300000,"facilityMapID":4150},"4151":{"facilityID":300010,"facilityMapID":4151},"4152":{"facilityID":300020,"facilityMapID":4152},"4153":{"facilityID":300030,"facilityMapID":4153},"4160":{"facilityID":301000,"facilityMapID":4160},"4161":{"facilityID":301010,"facilityMapID":4161},"4162":{"facilityID":301020,"facilityMapID":4162},"4163":{"facilityID":301030,"facilityMapID":4163},"4170":{"facilityID":302000,"facilityMapID":4170},"4171":{"facilityID":302010,"facilityMapID":4171},"4172":{"facilityID":302020,"facilityMapID":4172},"4173":{"facilityID":302030,"facilityMapID":4173},"4180":{"facilityID":303000,"facilityMapID":4180},"4181":{"facilityID":303010,"facilityMapID":4181},"4182":{"facilityID":303020,"facilityMapID":4182},"4183":{"facilityID":303030,"facilityMapID":4183},"4190":{"facilityID":304000,"facilityMapID":4190},"4191":{"facilityID":304010,"facilityMapID":4191},"4192":{"facilityID":304020,"facilityMapID":4192},"4193":{"facilityID":304030,"facilityMapID":4193},"4200":{"facilityID":305000,"facilityMapID":4200},"4201":{"facilityID":305010,"facilityMapID":4201},"4202":{"facilityID":305020,"facilityMapID":4202},"4203":{"facilityID":305030,"facilityMapID":4203},"4210":{"facilityID":306000,"facilityMapID":4210},"4211":{"facilityID":306010,"facilityMapID":4211},"4212":{"facilityID":306020,"facilityMapID":4212},"4213":{"facilityID":306030,"facilityMapID":4213},"4220":{"facilityID":307000,"facilityMapID":4220},"4221":{"facilityID":307010,"facilityMapID":4221},"4222":{"facilityID":307020,"facilityMapID":4222},"4223":{"facilityID":307030,"facilityMapID":4223},"4230":{"facilityID":308000,"facilityMapID":4230},"4240":{"facilityID":309000,"facilityMapID":4240},"4250":{"facilityID":310000,"facilityMapID":4250},"4260":{"facilityID":287010,"facilityMapID":4260},"4261":{"facilityID":287020,"facilityMapID":4261},"4262":{"facilityID":287030,"facilityMapID":4262},"4263":{"facilityID":287040,"facilityMapID":4263},"4264":{"facilityID":287050,"facilityMapID":4264},"4265":{"facilityID":287060,"facilityMapID":4265},"4266":{"facilityID":287070,"facilityMapID":4266},"4267":{"facilityID":287080,"facilityMapID":4267},"4268":{"facilityID":287090,"facilityMapID":4268},"4269":{"facilityID":287100,"facilityMapID":4269},"4270":{"facilityID":287110,"facilityMapID":4270},"4271":{"facilityID":287120,"facilityMapID":4271},"6001":{"facilityID":200000,"facilityMapID":6001},"6002":{"facilityID":201000,"facilityMapID":6002},"6003":{"facilityID":203000,"facilityMapID":6003},"6101":{"facilityID":204000,"facilityMapID":6101},"6102":{"facilityID":205000,"facilityMapID":6102},"6103":{"facilityID":206000,"facilityMapID":6103},"6111":{"facilityID":207000,"facilityMapID":6111},"6112":{"facilityID":208000,"facilityMapID":6112},"6113":{"facilityID":209000,"facilityMapID":6113},"6121":{"facilityID":210000,"facilityMapID":6121},"6122":{"facilityID":211000,"facilityMapID":6122},"6123":{"facilityID":212000,"facilityMapID":6123},"6201":{"facilityID":213000,"facilityMapID":6201},"6202":{"facilityID":214000,"facilityMapID":6202},"6203":{"facilityID":215000,"facilityMapID":6203},"6204":{"facilityID":216000,"facilityMapID":6204},"6205":{"facilityID":217000,"facilityMapID":6205},"6206":{"facilityID":218000,"facilityMapID":6206},"6207":{"facilityID":219000,"facilityMapID":6207},"6208":{"facilityID":220000,"facilityMapID":6208},"6209":{"facilityID":221000,"facilityMapID":6209},"6301":{"facilityID":222000,"facilityMapID":6301},"6302":{"facilityID":222010,"facilityMapID":6302},"6303":{"facilityID":222020,"facilityMapID":6303},"6304":{"facilityID":222030,"facilityMapID":6304},"6305":{"facilityID":222040,"facilityMapID":6305},"6306":{"facilityID":222050,"facilityMapID":6306},"6307":{"facilityID":222060,"facilityMapID":6307},"6308":{"facilityID":260004,"facilityMapID":6308},"6309":{"facilityID":222080,"facilityMapID":6309},"6310":{"facilityID":222090,"facilityMapID":6310},"6311":{"facilityID":222100,"facilityMapID":6311},"6312":{"facilityID":222110,"facilityMapID":6312},"6313":{"facilityID":222120,"facilityMapID":6313},"6314":{"facilityID":222130,"facilityMapID":6314},"6316":{"facilityID":222150,"facilityMapID":6316},"6317":{"facilityID":222160,"facilityMapID":6317},"6318":{"facilityID":222170,"facilityMapID":6318},"6319":{"facilityID":222180,"facilityMapID":6319},"6320":{"facilityID":222190,"facilityMapID":6320},"6323":{"facilityID":222220,"facilityMapID":6323},"6324":{"facilityID":222230,"facilityMapID":6324},"6325":{"facilityID":222240,"facilityMapID":6325},"6326":{"facilityID":222250,"facilityMapID":6326},"6328":{"facilityID":222270,"facilityMapID":6328},"6329":{"facilityID":222280,"facilityMapID":6329},"6330":{"facilityID":222300,"facilityMapID":6330},"6331":{"facilityID":222310,"facilityMapID":6331},"6332":{"facilityID":222320,"facilityMapID":6332},"6333":{"facilityID":222330,"facilityMapID":6333},"6334":{"facilityID":222340,"facilityMapID":6334},"6335":{"facilityID":222350,"facilityMapID":6335},"6336":{"facilityID":222360,"facilityMapID":6336},"6337":{"facilityID":222370,"facilityMapID":6337},"6338":{"facilityID":222380,"facilityMapID":6338},"6339":{"facilityID":222290,"facilityMapID":6339},"6340":{"facilityID":204001,"facilityMapID":6340},"6341":{"facilityID":204002,"facilityMapID":6341},"6342":{"facilityID":204003,"facilityMapID":6342},"6343":{"facilityID":205001,"facilityMapID":6343},"6344":{"facilityID":205002,"facilityMapID":6344},"6345":{"facilityID":205003,"facilityMapID":6345},"6346":{"facilityID":206001,"facilityMapID":6346},"6347":{"facilityID":206002,"facilityMapID":6347},"6348":{"facilityID":207001,"facilityMapID":6348},"6349":{"facilityID":207002,"facilityMapID":6349},"6350":{"facilityID":207003,"facilityMapID":6350},"6351":{"facilityID":208001,"facilityMapID":6351},"6352":{"facilityID":208002,"facilityMapID":6352},"6353":{"facilityID":209001,"facilityMapID":6353},"6354":{"facilityID":209002,"facilityMapID":6354},"6355":{"facilityID":209003,"facilityMapID":6355},"6356":{"facilityID":210001,"facilityMapID":6356},"6357":{"facilityID":210002,"facilityMapID":6357},"6358":{"facilityID":210003,"facilityMapID":6358},"6359":{"facilityID":211001,"facilityMapID":6359},"6360":{"facilityID":211002,"facilityMapID":6360},"6361":{"facilityID":212001,"facilityMapID":6361},"6362":{"facilityID":212002,"facilityMapID":6362},"6363":{"facilityID":212003,"facilityMapID":6363},"18001":{"facilityID":230000,"facilityMapID":18001},"18002":{"facilityID":231000,"facilityMapID":18002},"18003":{"facilityID":232000,"facilityMapID":18003},"18004":{"facilityID":233000,"facilityMapID":18004},"18005":{"facilityID":234000,"facilityMapID":18005},"18006":{"facilityID":235000,"facilityMapID":18006},"18007":{"facilityID":236000,"facilityMapID":18007},"18008":{"facilityID":237000,"facilityMapID":18008},"18009":{"facilityID":250000,"facilityMapID":18009},"18010":{"facilityID":239000,"facilityMapID":18010},"18011":{"facilityID":240000,"facilityMapID":18011},"18012":{"facilityID":241000,"facilityMapID":18012},"18013":{"facilityID":242000,"facilityMapID":18013},"18014":{"facilityID":243000,"facilityMapID":18014},"18015":{"facilityID":244000,"facilityMapID":18015},"18016":{"facilityID":245000,"facilityMapID":18016},"18017":{"facilityID":246000,"facilityMapID":18017},"18018":{"facilityID":247000,"facilityMapID":18018},"18019":{"facilityID":248000,"facilityMapID":18019},"18020":{"facilityID":249000,"facilityMapID":18020},"18021":{"facilityID":238000,"facilityMapID":18021},"18022":{"facilityID":251000,"facilityMapID":18022},"18023":{"facilityID":252000,"facilityMapID":18023},"18024":{"facilityID":253000,"facilityMapID":18024},"18025":{"facilityID":254000,"facilityMapID":18025},"18026":{"facilityID":255000,"facilityMapID":18026},"18027":{"facilityID":256000,"facilityMapID":18027},"18028":{"facilityID":257000,"facilityMapID":18028},"18029":{"facilityID":258000,"facilityMapID":18029},"18030":{"facilityID":259000,"facilityMapID":18030},"18031":{"facilityID":260000,"facilityMapID":18031},"18032":{"facilityID":244100,"facilityMapID":18032},"18033":{"facilityID":244200,"facilityMapID":18033},"18034":{"facilityID":244300,"facilityMapID":18034},"18035":{"facilityID":310005,"facilityMapID":18035},"18036":{"facilityID":244500,"facilityMapID":18036},"18037":{"facilityID":244600,"facilityMapID":18037},"18038":{"facilityID":260010,"facilityMapID":18038},"18046":{"facilityID":251010,"facilityMapID":18046},"18047":{"facilityID":251020,"facilityMapID":18047},"18048":{"facilityID":251030,"facilityMapID":18048},"18049":{"facilityID":252010,"facilityMapID":18049},"18050":{"facilityID":252020,"facilityMapID":18050},"18051":{"facilityID":253010,"facilityMapID":18051},"18052":{"facilityID":253020,"facilityMapID":18052},"18053":{"facilityID":253030,"facilityMapID":18053},"18054":{"facilityID":253040,"facilityMapID":18054},"18055":{"facilityID":254010,"facilityMapID":18055},"18056":{"facilityID":254020,"facilityMapID":18056},"18057":{"facilityID":254030,"facilityMapID":18057},"18058":{"facilityID":255010,"facilityMapID":18058},"18059":{"facilityID":255020,"facilityMapID":18059},"18060":{"facilityID":255030,"facilityMapID":18060},"18061":{"facilityID":256010,"facilityMapID":18061},"18062":{"facilityID":256020,"facilityMapID":18062},"18063":{"facilityID":256030,"facilityMapID":18063},"18064":{"facilityID":257010,"facilityMapID":18064},"18065":{"facilityID":257020,"facilityMapID":18065},"18066":{"facilityID":257030,"facilityMapID":18066},"18067":{"facilityID":244610,"facilityMapID":18067},"18068":{"facilityID":244620,"facilityMapID":18068}};

var mapAssetsUrl = 'https://maps.ps2alerts.com';

var tiles_indar = L.tileLayer(mapAssetsUrl + '/indar_tiles/{z}/map_tile_{x}_{y}.png', {
    bounds: [[-18, 18], [-248, 248]],
    continuousWorld: false,
    noWrap: true,
    maxNativeZoom: 5,
    maxZoom: 5,
    minZoom: 1
});

var tiles_esamir = L.tileLayer(mapAssetsUrl + '/esamir_tiles/{z}/map_tile_{x}_{y}.png', {
    bounds: [[-18, 18], [-248, 248]],
    continuousWorld: false,
    noWrap: true,
    maxNativeZoom: 5,
    maxZoom: 7,
    minZoom: 1
});

var tiles_amerish = L.tileLayer(mapAssetsUrl + '/amerish_tiles/{z}/map_tile_{x}_{y}.png', {
    bounds: [[-18, 18], [-248, 248]],
    continuousWorld: false,
    noWrap: true,
    maxNativeZoom: 5,
    maxZoom: 7,
    minZoom: 1
});

var tiles_hossin = L.tileLayer(mapAssetsUrl + '/hossin_tiles/{z}/map_tile_{x}_{y}.png', {
    bounds: [[-18, 18], [-248, 248]],
    continuousWorld: false,
    noWrap: true,
    maxNativeZoom: 5,
    maxZoom: 7,
    minZoom: 1
});

var icons = [];
for (i = 0; i <= 3; i++) {
    icons[i] = {};
    icons[i]['icon tech-plant'] = L.divIcon({
        className: 'facility_icon facility_4_' + i,
        iconSize: [32, 32]
    });

    icons[i]["icon warpgate"] = L.divIcon({
        className: 'facility_icon facility_7_' + i,
        iconSize: [32, 32],
    });

    icons[i]["icon large-outpost"] = L.divIcon({
        className: 'facility_icon facility_5_' + i,
        iconSize: [32, 32]
    });

    icons[i]["icon small-outpost"] = L.divIcon({
        className: 'facility_icon facility_6_' + i,
        iconSize: [32, 32]
    });

    icons[i]["icon amp-station"] = L.divIcon({
        className: 'facility_icon facility_2_' + i,
        iconSize: [32, 32]
    });

    icons[i]["icon bio-lab"] = L.divIcon({
        className: 'facility_icon facility_3_' + i,
        iconSize: [32, 32]
    });
}

function init_map(callback) {

    //refreshMap(map_data);
    load_map(eventZone);

    map = L.map('map', {
        crs: L.CRS.Simple,
        zoom: 2,
        center: ps2_territory_layer.getBounds().getCenter(),
        layers: [ps2_territory_layer, ps2_marker_layer, (!!ps2_map_active_tiles ? ps2_map_active_tiles : null)],
attributionControl : false
    });

    // TODO: re enable layers
    //map_control = L.control.layers({"Indar": tiles_indar, "Esamir": tiles_esamir, "Amerish": tiles_amerish, "Hossin": tiles_hossin}, {"Facilities": ps2_marker_layer, "Territory control": ps2_territory_layer}).addTo(map);
    map_control = L.control.layers(null, {"Facilities": ps2_marker_layer, "Territory control": ps2_territory_layer}).addTo(map);

    if (!!callback)
        callback();

}
;

// TODO: execute on websocket update
//refreshMap(map_data);


function addMarkers(markers) {

    removeMarkers();
    for (i in markers) {
//        console.log("Adding marker for", i, markers[i].label.message, markers[i]);
        var plotll = new L.LatLng(markers[i].lat, markers[i].lng);

        var markerFaction = 0;
        if (FacDataInverse.hasOwnProperty(i)) {
            var facid = FacDataInverse[i].facilityID;

            if (!!mapStats && mapStats.hasOwnProperty(facid)) {
                markerFaction = mapStats[facid].facilityOwner;
            }
        }

        var plotmark = new L.Marker(plotll, {icon: icons[markerFaction][markers[i].icon.className]});
        plotmark.data = markers[i];
        ps2_marker_layer.addLayer(plotmark);

        plotmark.bindPopup("<h3>" + markers[i].label.message + "</h3>Last cap by: DIG<br>Total cap time: inf min");
    }
}



function removeMarkers() {
    old_layer = ps2_marker_layer;
    ps2_marker_layer = L.layerGroup();

    if (map !== null) {
        map.removeLayer(old_layer);
        ps2_marker_layer.addTo(map);
    }
    if (map_control !== null) {
        map_control.removeLayer(old_layer);
        map_control.addOverlay(ps2_marker_layer, "Facilities");
    }
}

function setTiles(tileSet) {
    if (ps2_map_active_tiles !== null) {
        map.removeLayer(ps2_map_active_tiles);
    }

    ps2_map_active_tiles = tileSet;
    if (!!map) {
        map.addLayer(ps2_map_active_tiles);
    }
}

function load_map(map) {
    switch (map) {
        case 2:
            map_data = map_data_indar;
            setTiles(tiles_indar);
            break;
        case 4:
            map_data = map_data_hossin;
            setTiles(tiles_hossin);
            break;
        case 6:
            map_data = map_data_amerish;
            setTiles(tiles_amerish);
            break;
        case 8:
            map_data = map_data_esamir;
            setTiles(tiles_esamir);
            break;
    }

    refreshMap();
}

function refreshMap() {
    // reload overlay
    if (!!ps2_territory_layer) {
        if (!!map) {
            map.removeLayer(ps2_territory_layer);
        }
        if (!!map_control) {
            map_control.removeLayer(ps2_territory_layer);
        }
    }

    ps2_territory_layer = L.geoJson(map_data.geoJSON, {
        style: function (feature) {
            color = 'black';


            if (FacDataInverse.hasOwnProperty(feature.id)) {
                var facid = FacDataInverse[feature.id].facilityID;

                if (!!mapStats && mapStats.hasOwnProperty(facid)) {

                    switch (mapStats[facid].facilityOwner) {
                        case "1":
                            color = vsMapColor;
                            break;
                        case "2":
                            color = ncMapColor;
                            break;
                        case "3":
                            color = trMapColor;
                            break;
                    }
                }
            }


            return {
                fillColor: color,
                weight: 1.3,
                opacity: 1,
                color: 'black',
                dashArray: '1',
                fillOpacity: 0.3
//                stroke: false,
            };
        }
    });

    if (!!map) {
        ps2_territory_layer.addTo(map);
    }
    if (!!map_control) {
        map_control.addOverlay(ps2_territory_layer, "Territory control");
    }

    addMarkers(map_data.markers);
}
