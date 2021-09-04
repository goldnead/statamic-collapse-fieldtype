import CollapseFieldtype from "./fieldtypes/Collapse.vue";
 
 Statamic.booting(() => {
   Statamic.component("collapse-fieldtype", CollapseFieldtype);
 });
 