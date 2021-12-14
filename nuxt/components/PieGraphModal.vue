<template>
    <v-card>
        <v-card-title>
            <span>{{focusData.name}}</span>
            <v-spacer></v-spacer>
            <span v-if="$route.query.year">{{$route.query.year}}年</span>
            <span v-if="$route.query.month">{{$route.query.month}}月</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text class="pa-0">
            <PieGraphCard :propsDatas="focusData.datas" :center="focusData.monthly_sum_minute" mode="modal" />
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

<script>
export default {
    props: ["focusData"],
};
</script>

<style>
</style>