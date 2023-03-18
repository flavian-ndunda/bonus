<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php
$popup_background_gradients = json_decode('[{"name":"Sublime Light","css":"linear-gradient(rgb(252, 92, 125), rgb(106, 130, 251))"},{"name":"Sublime Vivid","css":"linear-gradient(rgb(252, 70, 107), rgb(63, 94, 251))"},{"name":"Bighead","css":"linear-gradient(rgb(201, 75, 75), rgb(75, 19, 79))"},{"name":"Taran Tado","css":"linear-gradient(rgb(35, 7, 77), rgb(204, 83, 51))"},{"name":"Relaxing red","css":"linear-gradient(rgb(255, 251, 213), rgb(178, 10, 44))"},{"name":"Lawrencium","css":"linear-gradient(rgb(15, 12, 41), rgb(48, 43, 99), rgb(36, 36, 62))"},{"name":"Ohhappiness","css":"linear-gradient(rgb(0, 176, 155), rgb(150, 201, 61))"},{"name":"Delicate","css":"linear-gradient(rgb(211, 204, 227), rgb(233, 228, 240))"},{"name":"Selenium","css":"linear-gradient(rgb(60, 59, 63), rgb(96, 92, 60))"},{"name":"Sulphur","css":"linear-gradient(rgb(202, 197, 49), rgb(243, 249, 167))"},{"name":"Pink Flavour","css":"linear-gradient(rgb(128, 0, 128), rgb(255, 192, 203))"},{"name":"Orange Fun","css":"linear-gradient(rgb(252, 74, 26), rgb(247, 183, 51))"},{"name":"Velvet Sun","css":"linear-gradient(rgb(225, 238, 195), rgb(240, 80, 83))"},{"name":"Digital Water","css":"linear-gradient(rgb(116, 235, 213), rgb(172, 182, 229))"},{"name":"Lithium","css":"linear-gradient(rgb(109, 96, 39), rgb(211, 203, 184))"},{"name":"Argon","css":"linear-gradient(rgb(3, 0, 30), rgb(115, 3, 192), rgb(236, 56, 188), rgb(253, 239, 249))"},{"name":"Hydrogen","css":"linear-gradient(rgb(102, 125, 182), rgb(0, 130, 200), rgb(0, 130, 200), rgb(102, 125, 182))"},{"name":"Zinc","css":"linear-gradient(rgb(173, 169, 150), rgb(242, 242, 242), rgb(219, 219, 219), rgb(234, 234, 234))"},{"name":"Velvet Sun","css":"linear-gradient(rgb(225, 238, 195), rgb(240, 80, 83))"},{"name":"King Yna","css":"linear-gradient(rgb(26, 42, 108), rgb(178, 31, 31), rgb(253, 187, 45))"},{"name":"Summer","css":"linear-gradient(rgb(34, 193, 195), rgb(253, 187, 45))"},{"name":"Orange Coral","css":"linear-gradient(rgb(255, 153, 102), rgb(255, 94, 98))"},{"name":"Purpink","css":"linear-gradient(rgb(127, 0, 255), rgb(225, 0, 255))"},{"name":"Dull","css":"linear-gradient(rgb(201, 214, 255), rgb(226, 226, 226))"},{"name":"Kimoby Is The New Blue","css":"linear-gradient(rgb(57, 106, 252), rgb(41, 72, 255))"},{"name":"Broken Hearts","css":"linear-gradient(rgb(217, 167, 199), rgb(255, 252, 220))"},{"name":"Clot","css":"linear-gradient(rgb(7, 0, 0), rgb(76, 0, 1), rgb(7, 0, 0))"},{"name":"Shift","css":"linear-gradient(rgb(0, 0, 0), rgb(229, 0, 141), rgb(255, 7, 11))"},{"name":"Subu","css":"linear-gradient(rgb(12, 235, 235), rgb(32, 227, 178), rgb(41, 255, 198))"},{"name":"Socialive","css":"linear-gradient(rgb(6, 190, 182), rgb(72, 177, 191))"},{"name":"Crimson Tide","css":"linear-gradient(rgb(100, 43, 115), rgb(198, 66, 110))"},{"name":"Telegram","css":"linear-gradient(rgb(28, 146, 210), rgb(242, 252, 254))"},{"name":"Terminal","css":"linear-gradient(rgb(0, 0, 0), rgb(15, 155, 15))"},{"name":"Scooter","css":"linear-gradient(rgb(54, 209, 220), rgb(91, 134, 229))"},{"name":"Alive","css":"linear-gradient(rgb(203, 53, 107), rgb(189, 63, 50))"},{"name":"Relay","css":"linear-gradient(rgb(58, 28, 113), rgb(215, 109, 119), rgb(255, 175, 123))"},{"name":"Meridian","css":"linear-gradient(rgb(40, 60, 134), rgb(69, 162, 71))"},{"name":"Compare Now","css":"linear-gradient(rgb(239, 59, 54), rgb(255, 255, 255))"},{"name":"Mello","css":"linear-gradient(rgb(192, 57, 43), rgb(142, 68, 173))"},{"name":"Crystal Clear","css":"linear-gradient(rgb(21, 153, 87), rgb(21, 87, 153))"},{"name":"Visions of Grandeur","css":"linear-gradient(rgb(0, 0, 70), rgb(28, 181, 224))"},{"name":"Chitty Chitty Bang Bang","css":"linear-gradient(rgb(0, 121, 145), rgb(120, 255, 214))"},{"name":"Blue Skies","css":"linear-gradient(rgb(86, 204, 242), rgb(47, 128, 237))"},{"name":"Sunkist","css":"linear-gradient(rgb(242, 153, 74), rgb(242, 201, 76))"},{"name":"Coal","css":"linear-gradient(rgb(235, 87, 87), rgb(0, 0, 0))"},{"name":"Html","css":"linear-gradient(rgb(228, 77, 38), rgb(241, 101, 41))"},{"name":"Cinnamint","css":"linear-gradient(rgb(74, 194, 154), rgb(189, 255, 243))"},{"name":"Maldives","css":"linear-gradient(rgb(178, 254, 250), rgb(14, 210, 247))"},{"name":"Mini","css":"linear-gradient(rgb(48, 232, 191), rgb(255, 130, 53))"},{"name":"Sha la la","css":"linear-gradient(rgb(214, 109, 117), rgb(226, 149, 135))"},{"name":"Purplepine","css":"linear-gradient(rgb(32, 0, 44), rgb(203, 180, 212))"},{"name":"Celestial","css":"linear-gradient(rgb(195, 55, 100), rgb(29, 38, 113))"},{"name":"Learning and Leading","css":"linear-gradient(rgb(247, 151, 30), rgb(255, 210, 0))"},{"name":"Pacific Dream","css":"linear-gradient(rgb(52, 232, 158), rgb(15, 52, 67))"},{"name":"Venice","css":"linear-gradient(rgb(97, 144, 232), rgb(167, 191, 232))"},{"name":"Orca","css":"linear-gradient(rgb(68, 160, 141), rgb(9, 54, 55))"},{"name":"Love and Liberty","css":"linear-gradient(rgb(32, 1, 34), rgb(111, 0, 0))"},{"name":"Very Blue","css":"linear-gradient(rgb(5, 117, 230), rgb(2, 27, 121))"},{"name":"Can You Feel The Love Tonight","css":"linear-gradient(rgb(69, 104, 220), rgb(176, 106, 179))"},{"name":"The Blue Lagoon","css":"linear-gradient(rgb(67, 198, 172), rgb(25, 22, 84))"},{"name":"Under the Lake","css":"linear-gradient(rgb(9, 48, 40), rgb(35, 122, 87))"},{"name":"Honey Dew","css":"linear-gradient(rgb(67, 198, 172), rgb(248, 255, 174))"},{"name":"Roseanna","css":"linear-gradient(rgb(255, 175, 189), rgb(255, 195, 160))"},{"name":"What lies Beyond","css":"linear-gradient(rgb(240, 242, 240), rgb(0, 12, 64))"},{"name":"Rose Colored Lenses","css":"linear-gradient(rgb(232, 203, 192), rgb(99, 111, 164))"},{"name":"EasyMed","css":"linear-gradient(rgb(220, 227, 91), rgb(69, 182, 73))"},{"name":"Cocoaa Ice","css":"linear-gradient(rgb(192, 192, 170), rgb(28, 239, 255))"},{"name":"Jaipur","css":"linear-gradient(rgb(219, 230, 246), rgb(197, 121, 109))"},{"name":"Vice City","css":"linear-gradient(rgb(52, 148, 230), rgb(236, 110, 173))"},{"name":"Mild","css":"linear-gradient(rgb(103, 178, 111), rgb(76, 162, 205))"},{"name":"Dawn","css":"linear-gradient(rgb(243, 144, 79), rgb(59, 67, 113))"},{"name":"Ibiza Sunset","css":"linear-gradient(rgb(238, 9, 121), rgb(255, 106, 0))"},{"name":"Radar","css":"linear-gradient(rgb(167, 112, 239), rgb(207, 139, 243), rgb(253, 185, 155))"},{"name":"80s Purple","css":"linear-gradient(rgb(65, 41, 90), rgb(47, 7, 67))"},{"name":"Black Ros\u00e9","css":"linear-gradient(rgb(244, 196, 243), rgb(252, 103, 250))"},{"name":"Brady Brady Fun Fun","css":"linear-gradient(rgb(0, 195, 255), rgb(255, 255, 28))"},{"name":"Eds Sunset Gradient","css":"linear-gradient(rgb(255, 126, 95), rgb(254, 180, 123))"},{"name":"Snapchat","css":"linear-gradient(rgb(255, 252, 0), rgb(255, 255, 255))"},{"name":"Cosmic Fusion","css":"linear-gradient(rgb(255, 0, 204), rgb(51, 51, 153))"},{"name":"Nepal","css":"linear-gradient(rgb(222, 97, 97), rgb(38, 87, 235))"},{"name":"Azure Pop","css":"linear-gradient(rgb(239, 50, 217), rgb(137, 255, 253))"},{"name":"Love Couple","css":"linear-gradient(rgb(58, 97, 134), rgb(137, 37, 62))"},{"name":"Disco","css":"linear-gradient(rgb(78, 205, 196), rgb(85, 98, 112))"},{"name":"Limeade","css":"linear-gradient(rgb(161, 255, 206), rgb(250, 255, 209))"},{"name":"Dania","css":"linear-gradient(rgb(190, 147, 197), rgb(123, 198, 204))"},{"name":"50 Shades of Grey","css":"linear-gradient(rgb(189, 195, 199), rgb(44, 62, 80))"},{"name":"Dusk","css":"linear-gradient(rgb(255, 216, 155), rgb(25, 84, 123))"},{"name":"IIIT Delhi","css":"linear-gradient(rgb(128, 128, 128), rgb(63, 173, 168))"},{"name":"Sun on the Horizon","css":"linear-gradient(rgb(252, 234, 187), rgb(248, 181, 0))"},{"name":"Blood Red","css":"linear-gradient(rgb(248, 80, 50), rgb(231, 56, 39))"},{"name":"Sherbert","css":"linear-gradient(rgb(247, 157, 0), rgb(100, 243, 140))"},{"name":"Firewatch","css":"linear-gradient(rgb(203, 45, 62), rgb(239, 71, 58))"},{"name":"Lush","css":"linear-gradient(rgb(86, 171, 47), rgb(168, 224, 99))"},{"name":"Frost","css":"linear-gradient(rgb(0, 4, 40), rgb(0, 78, 146))"},{"name":"Mauve","css":"linear-gradient(rgb(66, 39, 90), rgb(115, 75, 109))"},{"name":"Royal","css":"linear-gradient(rgb(20, 30, 48), rgb(36, 59, 85))"},{"name":"Minimal Red","css":"linear-gradient(rgb(240, 0, 0), rgb(220, 40, 30))"},{"name":"Dusk","css":"linear-gradient(rgb(44, 62, 80), rgb(253, 116, 108))"},{"name":"Deep Sea Space","css":"linear-gradient(rgb(44, 62, 80), rgb(76, 161, 175))"},{"name":"Grapefruit Sunset","css":"linear-gradient(rgb(233, 100, 67), rgb(144, 78, 149))"},{"name":"Sunset","css":"linear-gradient(rgb(11, 72, 107), rgb(245, 98, 23))"},{"name":"Solid Vault","css":"linear-gradient(rgb(58, 123, 213), rgb(58, 96, 115))"},{"name":"Bright Vault","css":"linear-gradient(rgb(0, 210, 255), rgb(146, 141, 171))"},{"name":"Politics","css":"linear-gradient(rgb(33, 150, 243), rgb(244, 67, 54))"},{"name":"Sweet Morning","css":"linear-gradient(rgb(255, 95, 109), rgb(255, 195, 113))"},{"name":"Sylvia","css":"linear-gradient(rgb(255, 75, 31), rgb(255, 144, 104))"},{"name":"Transfile","css":"linear-gradient(rgb(22, 191, 253), rgb(203, 48, 102))"},{"name":"Tranquil","css":"linear-gradient(rgb(238, 205, 163), rgb(239, 98, 159))"},{"name":"Red Ocean","css":"linear-gradient(rgb(29, 67, 80), rgb(164, 57, 49))"},{"name":"Shahabi","css":"linear-gradient(rgb(168, 0, 119), rgb(102, 255, 0))"},{"name":"Alihossein","css":"linear-gradient(rgb(247, 255, 0), rgb(219, 54, 164))"},{"name":"Ali","css":"linear-gradient(rgb(255, 75, 31), rgb(31, 221, 255))"},{"name":"Purple White","css":"linear-gradient(rgb(186, 83, 112), rgb(244, 226, 216))"},{"name":"Colors Of Sky","css":"linear-gradient(rgb(224, 234, 252), rgb(207, 222, 243))"},{"name":"Decent","css":"linear-gradient(rgb(76, 161, 175), rgb(196, 224, 229))"},{"name":"Deep Space","css":"linear-gradient(rgb(0, 0, 0), rgb(67, 67, 67))"},{"name":"Dark Skies","css":"linear-gradient(rgb(75, 121, 161), rgb(40, 62, 81))"},{"name":"Suzy","css":"linear-gradient(rgb(131, 77, 155), rgb(208, 78, 214))"},{"name":"Superman","css":"linear-gradient(rgb(0, 153, 247), rgb(241, 23, 18))"},{"name":"Nighthawk","css":"linear-gradient(rgb(41, 128, 185), rgb(44, 62, 80))"},{"name":"Forest","css":"linear-gradient(rgb(90, 63, 55), rgb(44, 119, 68))"},{"name":"Miami Dolphins","css":"linear-gradient(rgb(77, 160, 176), rgb(211, 157, 56))"},{"name":"Minnesota Vikings","css":"linear-gradient(rgb(86, 20, 176), rgb(219, 214, 92))"},{"name":"Christmas","css":"linear-gradient(rgb(47, 115, 54), rgb(170, 58, 56))"},{"name":"Joomla","css":"linear-gradient(rgb(30, 60, 114), rgb(42, 82, 152))"},{"name":"Pizelex","css":"linear-gradient(rgb(17, 67, 87), rgb(242, 148, 146))"},{"name":"Haikus","css":"linear-gradient(rgb(253, 116, 108), rgb(255, 144, 104))"},{"name":"Pale Wood","css":"linear-gradient(rgb(234, 205, 163), rgb(214, 174, 123))"},{"name":"Purplin","css":"linear-gradient(rgb(106, 48, 147), rgb(160, 68, 255))"},{"name":"Inbox","css":"linear-gradient(rgb(69, 127, 202), rgb(86, 145, 200))"},{"name":"Blush","css":"linear-gradient(rgb(178, 69, 146), rgb(241, 95, 121))"},{"name":"Back to the Future","css":"linear-gradient(rgb(192, 36, 37), rgb(240, 203, 53))"},{"name":"Poncho","css":"linear-gradient(rgb(64, 58, 62), rgb(190, 88, 105))"},{"name":"Green and Blue","css":"linear-gradient(rgb(194, 229, 156), rgb(100, 179, 244))"},{"name":"Light Orange","css":"linear-gradient(rgb(255, 183, 94), rgb(237, 143, 3))"},{"name":"Netflix","css":"linear-gradient(rgb(142, 14, 0), rgb(31, 28, 24))"},{"name":"Little Leaf","css":"linear-gradient(rgb(118, 184, 82), rgb(141, 194, 111))"},{"name":"Deep Purple","css":"linear-gradient(rgb(103, 58, 183), rgb(81, 45, 168))"},{"name":"Back To Earth","css":"linear-gradient(rgb(0, 201, 255), rgb(146, 254, 157))"},{"name":"Master Card","css":"linear-gradient(rgb(244, 107, 69), rgb(238, 168, 73))"},{"name":"Clear Sky","css":"linear-gradient(rgb(0, 92, 151), rgb(54, 55, 149))"},{"name":"Passion","css":"linear-gradient(rgb(229, 57, 53), rgb(227, 93, 91))"},{"name":"Timber","css":"linear-gradient(rgb(252, 0, 255), rgb(0, 219, 222))"},{"name":"Between Night and Day","css":"linear-gradient(rgb(44, 62, 80), rgb(52, 152, 219))"},{"name":"Sage Persuasion","css":"linear-gradient(rgb(204, 204, 178), rgb(117, 117, 25))"},{"name":"Lizard","css":"linear-gradient(rgb(48, 67, 82), rgb(215, 210, 204))"},{"name":"Piglet","css":"linear-gradient(rgb(238, 156, 167), rgb(255, 221, 225))"},{"name":"Dark Knight","css":"linear-gradient(rgb(186, 139, 2), rgb(24, 24, 24))"},{"name":"Curiosity blue","css":"linear-gradient(rgb(82, 82, 82), rgb(61, 114, 180))"},{"name":"Ukraine","css":"linear-gradient(rgb(0, 79, 249), rgb(255, 249, 76))"},{"name":"Green to dark","css":"linear-gradient(rgb(106, 145, 19), rgb(20, 21, 23))"},{"name":"Fresh Turboscent","css":"linear-gradient(rgb(241, 242, 181), rgb(19, 80, 88))"},{"name":"Koko Caramel","css":"linear-gradient(rgb(209, 145, 60), rgb(255, 209, 148))"},{"name":"Virgin America","css":"linear-gradient(rgb(123, 67, 151), rgb(220, 36, 48))"},{"name":"Portrait","css":"linear-gradient(rgb(142, 158, 171), rgb(238, 242, 243))"},{"name":"Turquoise flow","css":"linear-gradient(rgb(19, 106, 138), rgb(38, 120, 113))"},{"name":"Vine","css":"linear-gradient(rgb(0, 191, 143), rgb(0, 21, 16))"},{"name":"Flickr","css":"linear-gradient(rgb(255, 0, 132), rgb(51, 0, 27))"},{"name":"Instagram","css":"linear-gradient(rgb(131, 58, 180), rgb(253, 29, 29), rgb(252, 176, 69))"},{"name":"Atlas","css":"linear-gradient(rgb(254, 172, 94), rgb(199, 121, 208), rgb(75, 192, 200))"},{"name":"Twitch","css":"linear-gradient(rgb(100, 65, 165), rgb(42, 8, 69))"},{"name":"Pastel Orange at the Sun","css":"linear-gradient(rgb(255, 179, 71), rgb(255, 204, 51))"},{"name":"Endless River","css":"linear-gradient(rgb(67, 206, 162), rgb(24, 90, 157))"},{"name":"Predawn","css":"linear-gradient(rgb(255, 161, 127), rgb(0, 34, 62))"},{"name":"Purple Bliss","css":"linear-gradient(rgb(54, 0, 51), rgb(11, 135, 147))"},{"name":"Talking To Mice Elf","css":"linear-gradient(rgb(148, 142, 153), rgb(46, 20, 55))"},{"name":"Hersheys","css":"linear-gradient(rgb(30, 19, 12), rgb(154, 132, 120))"},{"name":"Crazy Orange I","css":"linear-gradient(rgb(211, 131, 18), rgb(168, 50, 121))"},{"name":"Between The Clouds","css":"linear-gradient(rgb(115, 200, 169), rgb(55, 59, 68))"},{"name":"Metallic Toad","css":"linear-gradient(rgb(171, 186, 171), rgb(255, 255, 255))"},{"name":"Martini","css":"linear-gradient(rgb(253, 252, 71), rgb(36, 254, 65))"},{"name":"Friday","css":"linear-gradient(rgb(131, 164, 212), rgb(182, 251, 255))"},{"name":"ServQuick","css":"linear-gradient(rgb(72, 85, 99), rgb(41, 50, 60))"},{"name":"Behongo","css":"linear-gradient(rgb(82, 194, 52), rgb(6, 23, 0))"},{"name":"SoundCloud","css":"linear-gradient(rgb(254, 140, 0), rgb(248, 54, 0))"},{"name":"Facebook Messenger","css":"linear-gradient(rgb(0, 198, 255), rgb(0, 114, 255))"},{"name":"Shore","css":"linear-gradient(rgb(112, 225, 245), rgb(255, 209, 148))"},{"name":"Cheer Up Emo Kid","css":"linear-gradient(rgb(85, 98, 112), rgb(255, 107, 107))"},{"name":"Amethyst","css":"linear-gradient(rgb(157, 80, 187), rgb(110, 72, 170))"},{"name":"Man of Steel","css":"linear-gradient(rgb(120, 2, 6), rgb(6, 17, 97))"},{"name":"Neon Life","css":"linear-gradient(rgb(179, 255, 171), rgb(18, 255, 247))"},{"name":"Teal Love","css":"linear-gradient(rgb(170, 255, 169), rgb(17, 255, 189))"},{"name":"Red Mist","css":"linear-gradient(rgb(0, 0, 0), rgb(231, 76, 60))"},{"name":"Starfall","css":"linear-gradient(rgb(240, 194, 123), rgb(75, 18, 72))"},{"name":"Dance To Forget","css":"linear-gradient(rgb(255, 78, 80), rgb(249, 212, 35))"},{"name":"Parklife","css":"linear-gradient(rgb(173, 209, 0), rgb(123, 146, 10))"},{"name":"Cherryblossoms","css":"linear-gradient(rgb(251, 211, 233), rgb(187, 55, 125))"},{"name":"Shadow Night","css":"linear-gradient(rgb(0, 0, 0), rgb(83, 52, 109))"},{"name":"Ash","css":"linear-gradient(rgb(96, 108, 136), rgb(63, 76, 107))"},{"name":"Virgin","css":"linear-gradient(rgb(201, 255, 191), rgb(255, 175, 189))"},{"name":"Earthly","css":"linear-gradient(rgb(100, 145, 115), rgb(219, 213, 164))"},{"name":"Dirty Fog","css":"linear-gradient(rgb(185, 147, 214), rgb(140, 166, 219))"},{"name":"The Strain","css":"linear-gradient(rgb(135, 0, 0), rgb(25, 10, 5))"},{"name":"Reef","css":"linear-gradient(rgb(0, 210, 255), rgb(58, 123, 213))"},{"name":"Candy","css":"linear-gradient(rgb(211, 149, 155), rgb(191, 230, 186))"},{"name":"Autumn","css":"linear-gradient(rgb(218, 210, 153), rgb(176, 218, 185))"},{"name":"Winter","css":"linear-gradient(rgb(230, 218, 218), rgb(39, 64, 70))"},{"name":"Forever Lost","css":"linear-gradient(rgb(93, 65, 87), rgb(168, 202, 186))"},{"name":"Almost","css":"linear-gradient(rgb(221, 214, 243), rgb(250, 172, 168))"},{"name":"Moor","css":"linear-gradient(rgb(97, 97, 97), rgb(155, 197, 195))"},{"name":"Aqualicious","css":"linear-gradient(rgb(80, 201, 195), rgb(150, 222, 218))"},{"name":"Misty Meadow","css":"linear-gradient(rgb(33, 95, 0), rgb(228, 228, 217))"},{"name":"Kyoto","css":"linear-gradient(rgb(194, 21, 0), rgb(255, 197, 0))"},{"name":"Sirius Tamed","css":"linear-gradient(rgb(239, 239, 187), rgb(212, 211, 221))"},{"name":"Jonquil","css":"linear-gradient(rgb(255, 238, 238), rgb(221, 239, 187))"},{"name":"Petrichor","css":"linear-gradient(rgb(102, 102, 0), rgb(153, 153, 102))"},{"name":"A Lost Memory","css":"linear-gradient(rgb(222, 98, 98), rgb(255, 184, 140))"},{"name":"Vasily","css":"linear-gradient(rgb(233, 211, 98), rgb(51, 51, 51))"},{"name":"Blurry Beach","css":"linear-gradient(rgb(213, 51, 105), rgb(203, 173, 109))"},{"name":"Namn","css":"linear-gradient(rgb(167, 55, 55), rgb(122, 40, 40))"},{"name":"Day Tripper","css":"linear-gradient(rgb(248, 87, 166), rgb(255, 88, 88))"},{"name":"Pinot Noir","css":"linear-gradient(rgb(75, 108, 183), rgb(24, 40, 72))"},{"name":"Miaka","css":"linear-gradient(rgb(252, 53, 76), rgb(10, 191, 188))"},{"name":"Army","css":"linear-gradient(rgb(65, 77, 11), rgb(114, 122, 23))"},{"name":"Shrimpy","css":"linear-gradient(rgb(228, 58, 21), rgb(230, 82, 69))"},{"name":"Influenza","css":"linear-gradient(rgb(192, 72, 72), rgb(72, 0, 72))"},{"name":"Calm Darya","css":"linear-gradient(rgb(95, 44, 130), rgb(73, 160, 157))"},{"name":"Bourbon","css":"linear-gradient(rgb(236, 111, 102), rgb(243, 161, 131))"},{"name":"Stellar","css":"linear-gradient(rgb(116, 116, 191), rgb(52, 138, 199))"},{"name":"Clouds","css":"linear-gradient(rgb(236, 233, 230), rgb(255, 255, 255))"},{"name":"Moonrise","css":"linear-gradient(rgb(218, 226, 248), rgb(214, 164, 164))"},{"name":"Peach","css":"linear-gradient(rgb(237, 66, 100), rgb(255, 237, 188))"},{"name":"Dracula","css":"linear-gradient(rgb(220, 36, 36), rgb(74, 86, 157))"},{"name":"Mantle","css":"linear-gradient(rgb(36, 198, 220), rgb(81, 74, 157))"},{"name":"Titanium","css":"linear-gradient(rgb(40, 48, 72), rgb(133, 147, 152))"},{"name":"Opa","css":"linear-gradient(rgb(61, 126, 170), rgb(255, 228, 122))"},{"name":"Sea Blizz","css":"linear-gradient(rgb(28, 216, 210), rgb(147, 237, 199))"},{"name":"Midnight City","css":"linear-gradient(rgb(35, 37, 38), rgb(65, 67, 69))"},{"name":"Mystic","css":"linear-gradient(rgb(117, 127, 154), rgb(215, 221, 232))"},{"name":"Shroom Haze","css":"linear-gradient(rgb(92, 37, 141), rgb(67, 137, 162))"},{"name":"Moss","css":"linear-gradient(rgb(19, 78, 94), rgb(113, 178, 128))"},{"name":"Bora Bora","css":"linear-gradient(rgb(43, 192, 228), rgb(234, 236, 198))"},{"name":"Venice Blue","css":"linear-gradient(rgb(8, 80, 120), rgb(133, 216, 206))"},{"name":"Electric Violet","css":"linear-gradient(rgb(71, 118, 230), rgb(142, 84, 233))"},{"name":"Kashmir","css":"linear-gradient(rgb(97, 67, 133), rgb(81, 99, 149))"},{"name":"Steel Gray","css":"linear-gradient(rgb(31, 28, 44), rgb(146, 141, 171))"},{"name":"Mirage","css":"linear-gradient(rgb(22, 34, 42), rgb(58, 96, 115))"},{"name":"Juicy Orange","css":"linear-gradient(rgb(255, 128, 8), rgb(255, 200, 55))"},{"name":"Mojito","css":"linear-gradient(rgb(29, 151, 108), rgb(147, 249, 185))"},{"name":"Cherry","css":"linear-gradient(rgb(235, 51, 73), rgb(244, 92, 67))"},{"name":"Pinky","css":"linear-gradient(rgb(221, 94, 137), rgb(247, 187, 151))"},{"name":"Sea Weed","css":"linear-gradient(rgb(76, 184, 196), rgb(60, 211, 173))"},{"name":"Stripe","css":"linear-gradient(rgb(31, 162, 255), rgb(18, 216, 250), rgb(166, 255, 203))"},{"name":"Purple Paradise","css":"linear-gradient(rgb(29, 43, 100), rgb(248, 205, 218))"},{"name":"Sunrise","css":"linear-gradient(rgb(255, 81, 47), rgb(240, 152, 25))"},{"name":"Aqua Marine","css":"linear-gradient(rgb(26, 41, 128), rgb(38, 208, 206))"},{"name":"Aubergine","css":"linear-gradient(rgb(170, 7, 107), rgb(97, 4, 95))"},{"name":"Bloody Mary","css":"linear-gradient(rgb(255, 81, 47), rgb(221, 36, 118))"},{"name":"Mango Pulp","css":"linear-gradient(rgb(240, 152, 25), rgb(237, 222, 93))"},{"name":"Frozen","css":"linear-gradient(rgb(64, 59, 74), rgb(231, 233, 187))"},{"name":"Rose Water","css":"linear-gradient(rgb(229, 93, 135), rgb(95, 195, 228))"},{"name":"Horizon","css":"linear-gradient(rgb(0, 57, 115), rgb(229, 229, 190))"},{"name":"Monte Carlo","css":"linear-gradient(rgb(204, 149, 192), rgb(219, 212, 180), rgb(122, 161, 210))"},{"name":"Lemon Twist","css":"linear-gradient(rgb(60, 165, 92), rgb(181, 172, 73))"},{"name":"Emerald Water","css":"linear-gradient(rgb(52, 143, 80), rgb(86, 180, 211))"},{"name":"Intuitive Purple","css":"linear-gradient(rgb(218, 34, 255), rgb(151, 51, 238))"},{"name":"Green Beach","css":"linear-gradient(rgb(2, 170, 176), rgb(0, 205, 172))"},{"name":"Sunny Days","css":"linear-gradient(rgb(237, 229, 116), rgb(225, 245, 196))"},{"name":"Playing with Reds","css":"linear-gradient(rgb(211, 16, 39), rgb(234, 56, 77))"},{"name":"Harmonic Energy","css":"linear-gradient(rgb(22, 160, 133), rgb(244, 208, 63))"},{"name":"Cool Brown","css":"linear-gradient(rgb(96, 56, 19), rgb(178, 159, 148))"},{"name":"YouTube","css":"linear-gradient(rgb(229, 45, 39), rgb(179, 18, 23))"},{"name":"Noon to Dusk","css":"linear-gradient(rgb(255, 110, 127), rgb(191, 233, 255))"},{"name":"Hazel","css":"linear-gradient(rgb(119, 161, 211), rgb(121, 203, 202), rgb(230, 132, 174))"},{"name":"Nimvelo","css":"linear-gradient(rgb(49, 71, 85), rgb(38, 160, 218))"},{"name":"Sea Blue","css":"linear-gradient(rgb(43, 88, 118), rgb(78, 67, 118))"},{"name":"Blooker20","css":"linear-gradient(rgb(230, 92, 0), rgb(249, 212, 35))"},{"name":"Sexy Blue","css":"linear-gradient(rgb(33, 147, 176), rgb(109, 213, 237))"},{"name":"Purple Love","css":"linear-gradient(rgb(204, 43, 94), rgb(117, 58, 136))"},{"name":"DIMIGO","css":"linear-gradient(rgb(236, 0, 140), rgb(252, 103, 103))"},{"name":"Skyline","css":"linear-gradient(rgb(20, 136, 204), rgb(43, 50, 178))"},{"name":"Sel","css":"linear-gradient(rgb(0, 70, 127), rgb(165, 204, 130))"}]');
?>