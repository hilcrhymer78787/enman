<template>
    <div>
        <v-responsive class="pie_graph pa-5" aspect-ratio="1">
            <div class="pie_graph_cover">{{conversionTime(center)}}</div>
            <PieGraphWrap mode="users" :propsDatas="propsDatas" />
        </v-responsive>
        <v-divider></v-divider>
        <v-simple-table>
            <thead>
                <tr>
                    <th class="text-left"></th>
                    <th class="text-left">時間</th>
                    <th class="text-left">割合</th>
                </tr>
            </thead>
            <tbody>
                <tr @click="onClickData(data)" v-for="(data,index) in propsDatas" :key="index">
                    <td :style="`background-color:${$GRAPH_COLORS[index]};`">{{data.name}}</td>
                    <td>{{conversionTime(data.minute)}}</td>
                    <td>{{Math.floor((data.ratio)*1000)/10}}%</td>
                </tr>
            </tbody>
        </v-simple-table>
        <v-dialog @click:outside="focusData = null" :value="focusData" scrollable>
            <PieGraphModal v-if="focusData" :focusData="focusData" @onCloseDialog="focusData = ''" :subttl="subttl" />
        </v-dialog>
    </div>
</template>

<script lang="ts">
import Vue, { PropOptions } from "vue";
import { apiWorkReadAnalyticsResponseItemType } from "@/types/api/work/read/analytics/response";
export default Vue.extend({
    props: {
        propsDatas: Array as PropOptions<
            apiWorkReadAnalyticsResponseItemType[]
        >,
        center: Number,
        mode: String,
        subttl: String,
    },
    data() {
        return {
            focusData: null as apiWorkReadAnalyticsResponseItemType | null,
        };
    },
    methods: {
        onClickData(data: apiWorkReadAnalyticsResponseItemType) {
            if (data.minute == 0) {
                return;
            }
            if (this.mode == "modal") {
                return;
            }
            this.focusData = data;
        },
        conversionTime(minute: number): string {
            const time: number = Math.floor(minute / 60);
            const newMinute: number = minute % 60;
            if (time == 0) {
                return `${newMinute}分`;
            } else {
                return `${time}時間${newMinute}分`;
            }
        },
    },
});
</script>

<style lang="scss" scoped>
.pie_graph {
    position: relative;
    &_cover {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 25px;
        font-weight: bold;
        color: teal;
    }
}
tbody {
    tr {
        cursor: pointer;
    }
}
</style>