import '../css/addon.css';

import RasterFieldtype from './components/Fieldtypes/Raster.vue';

Statamic.booting(() => {

    Statamic.$components.register('raster-fieldtype', RasterFieldtype);

});
