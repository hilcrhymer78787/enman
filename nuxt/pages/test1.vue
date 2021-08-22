<template>
    <v-card>
        <v-card-text>
            <v-slider v-model="value" step="5" ticks="always" tick-size="6" :tick-labels="ticksLabels" :max="(ticksLabels.length - 1) * 5"></v-slider>
        </v-card-text>
        <pre>{{$data}}</pre>
    </v-card>
</template>
<script>
export default {
    data() {
        return {
            value: 0,
            ticksLabels: [],
        };
    },
    mounted() {
        for (let i = 0; i < 7; i++) {
            this.ticksLabels.push(String(i * 5));
        }
    },
};
</script>