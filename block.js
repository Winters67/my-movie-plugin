(()=>{"use strict";const e=window.ReactJSXRuntime,{registerBlockType:t}=wp.blocks,{TextControl:l,CheckboxControl:s}=wp.components,{InspectorControls:i}=wp.blockEditor;t("custom-plugin/film-block",{title:"Bloc Film",icon:"video-alt2",category:"widgets",attributes:{filmId:{type:"number",default:0},displayLatest:{type:"boolean",default:!0}},edit:({attributes:t,setAttributes:n})=>{const{filmId:o,displayLatest:r}=t;return(0,e.jsxs)(e.Fragment,{children:[(0,e.jsxs)(i,{children:[(0,e.jsx)(s,{label:"Afficher les derniers films",checked:r,onChange:e=>n({displayLatest:e})}),!r&&(0,e.jsx)(l,{label:"ID du film",value:o||"",type:"number",onChange:e=>n({filmId:parseInt(e)})})]}),(0,e.jsx)("div",{children:r?"Affichage des 3 derniers films":`Affichage du film avec ID : ${o}`})]})},save:()=>null})})();