<template>
    <v-card>
        <v-card-title class="d-block">
            <div>{{focusData.name}}</div>
            <div class="subttl">{{subttl}}</div>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text class="pa-0">
            <PieGraphCard :propsDatas="focusData.datas" :center="focusData.minute" mode="modal" :subttl="subttl" />
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
import Vue, { PropOptions } from 'vue'
import { apiWorkReadAnalyticsResponseItemType } from '@/types/api/work/read/analytics/response'
export default Vue.extend({
    props: {
        focusData: Object as PropOptions<apiWorkReadAnalyticsResponseItemType>,
        subttl: String,
    },
});
</script>

<style lang="scss" scoped>
.subttl {
    text-align: right;
    font-size: 15px;
    line-height: 15px;
}
</style>