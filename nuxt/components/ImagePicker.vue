<template>
    <v-card>
        <v-card-title>アイコン画像を選択してください</v-card-title>
        <v-divider></v-divider>
        <v-card-text class="pa-3" style="min-height:30vh;">
            <ul>
                <li v-for="n in 99" :key="n">
                    <v-card v-ripple>
                        <v-img @click="onSelectedImg(n)" :src="`https://picsum.photos/500/300?image=${n}`"></v-img>
                    </v-card>
                </li>
            </ul>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn @click="$emit('onCloseDialog')">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
    methods: {
        onSelectedImg(n: number) {
            this.$emit("onSelectedImg", n);
            this.$emit("onCloseDialog");
        },
    },
});
</script>

<style lang="scss" scoped>
ul {
    padding: 0 !important;
    display: flex;
    flex-wrap: wrap;
    li {
        list-style: none;
        width: 31%;
        margin-right: 3.5%;
        margin-bottom: 3.5%;
        &:nth-child(3n) {
            margin-right: 0;
        }
    }
}
</style>