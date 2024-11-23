<template>
    
    <div>
        <iframe
            v-if="previewWidth"
            class="raster-preview"
            ref="preview"
            :style="previewStyle"
            :src="previewSrc">
        </iframe>
    </div>
    
</template>

<script>

export default {

    mixins: [Fieldtype],

    mounted() {
        this.$nextTick(() => {
            this.measureElement();
            window.addEventListener('resize', this.measureElement);
            window.addEventListener('message', this.receiveMessage);
        });
        Statamic.$hooks.on('entry.saved', () => {
            this.$refs.preview.contentWindow.location.reload();
        });
    },

    data() {

        return {
            previewWidth: null,
            previewRatio: null,
        }

    },

    
    computed: {

        previewSrc() {
            return `/!/raster/statamic.hero?content=08f054d5-4254-4434-b89b-44d292c38a0c&preview=1&width=${this.previewWidth}`;
        },

        previewStyle() {
            if (!this.previewRatio) {
                return {};
            }
            return {
                height: 'auto',
                aspectRatio: this.previewRatio,
            }
        },

    },

    methods: {

        measureElement: _.debounce(function () {
            this.previewWidth = this.$el.getBoundingClientRect().width;
        }, 150),

        receiveMessage({ data }) {
            if (data.name !== 'raster.loaded') {
                return;
            }
            this.previewRatio = data.rect.width / data.rect.height;
        },

    }

}
</script>
