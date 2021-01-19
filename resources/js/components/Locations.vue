<template>
    <div id="harita" style="width:100%; height:500px">
        <iframe id="mapFrame" src="http://sehirharitasi.ibb.gov.tr/">
            <p>Tarayıcınız iframe özelliklerini desteklemiyor !</p>
        </iframe>
    </div>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';
    import axios from "axios";

    export default {

        data: () => ({
            ibbMAP: ''
        }),
        props:{
            address : '',
        },
        computed: {

        },

        watch: {

        },

        created () {

        },

        methods: {
            ...mapActions(['setCompany']),


            getLocations(){
                let vm = this;
                try {
                    vm.ibbMAP = new SehirHaritasiAPI({mapFrame:"mapFrame",apiKey:"d3db4f6f81294529813552f7ed5cdc17"}, function(){});
                    setTimeout(function(){
                        vm.ibbMAP.Panorama.Open({
                            lat: vm.address.latitude,
                            lon: vm.address.longitude,
                            angle: 10
                        });
                    },2000);

                }catch (e) {
                    return e;
                }
            }
        },
        mounted() {
            this.getLocations();
        }
    }
</script>
