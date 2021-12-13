<template>
    <v-text-field :value="roomMateNames(users)" readonly color="teal" :prepend-icon="prependIcon" :label="label" dense style="font-size:12px;"></v-text-field>
</template>
<script lang="ts">
import { PropOptions } from "vue";
export type userType = {
    id: number;
    name: string;
};
export default {
    props: {
        users: { type: Array } as PropOptions<userType[]>,
        prependIcon: { type: String },
        label: { type: String },
    },
    methods: {
        roomMateNames(users: userType[]): string {
            let outputData = "";
            users.forEach((user: userType, index: number): void => {
                if (index != 0) {
                    outputData = outputData + "、";
                }
                outputData = outputData + user.name;
            });
            return outputData;
        },
    },
};
</script>