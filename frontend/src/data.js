export const organizations = [
 {id:'dgapr',name:'المندوبية العامة لإدارة السجون وإعادة الإدماج',logo:'dgapr.png',aliases:'السجون DGAPR',specialties:[{id:'surveillant',name:'مراقب سجون من الدرجة الأولى',demo:true}]},
 {id:'dgsn',name:'الأمن الوطني',logo:'dgsn.jpg',aliases:'الشرطة DGSN',specialties:[]},
 {id:'justice',name:'وزارة العدل',logo:'justice.png',aliases:'justice المحاكم',specialties:[]}
];
// Exemples pédagogiques généraux ; aucun sujet officiel n'est revendiqué.
export const questions = [
 {q:'ما العدد التالي في المتتالية: 3، 6، 12، …؟',a:['18','24','30','36'],correct:1,why:'نضرب كل عدد في 2، إذن 12 × 2 = 24.'},
 {q:'إذا كان ثمن 3 دفاتر هو 24 درهماً، فما ثمن 5 دفاتر بنفس السعر؟',a:['32 درهماً','35 درهماً','40 درهماً','48 درهماً'],correct:2,why:'ثمن الدفتر هو 24 ÷ 3 = 8 دراهم؛ ثمن خمسة دفاتر هو 5 × 8 = 40 درهماً.'},
 {q:'ما مرادف كلمة «المثابرة»؟',a:['التردد','الاستمرار والاجتهاد','الانقطاع','التسرع'],correct:1,why:'المثابرة تعني الاستمرار والاجتهاد في العمل لتحقيق الهدف.'},
 {q:'اختر الجملة الصحيحة:',a:['إن الموظفون ملتزمون','إن الموظفين ملتزمون','إن الموظفين ملتزمين','إن الموظفون ملتزمين'],correct:1,why:'اسم إن منصوب بالياء لأنه جمع مذكر سالم، وخبرها مرفوع بالواو.'},
 {q:'Complétez : Nous … un examen.',a:['prépare','préparons','préparez','préparent'],correct:1,why:'مع الضمير Nous يُصرف الفعل préparer إلى préparons.'}
];
