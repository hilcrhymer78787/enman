<template>
    <div>
        <v-card v-if="works.minute" class="mb-5">
            <v-card-title class="d-block">
                <div>ユーザー別データ</div>
                <div class="subttl">{{subttl}}</div>
            </v-card-title>
            <PieGraphCard :propsDatas="works.users" :center="works.minute" :subttl="subttl" />
        </v-card>
        <v-card v-if="works.minute" class="mb-5">
            <v-card-title class="d-block">
                <div>タスク別データ</div>
                <div class="subttl">{{subttl}}</div>
            </v-card-title>
            <PieGraphCard :propsDatas="works.tasks" :center="works.minute" :subttl="subttl" />
        </v-card>
    </div>
</template>

<script lang="ts">
import Vue, { PropOptions } from "vue";
import { apiWorkReadAnalyticsResponseType } from "@/types/api/work/read/analytics/response";
export default Vue.extend({
    props: {
        works: Object as PropOptions<apiWorkReadAnalyticsResponseType>,
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