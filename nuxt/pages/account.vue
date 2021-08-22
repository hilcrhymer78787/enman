<template>
    <div>
        <pre>{{editMembers}}</pre>
        <pre>{{$data}}</pre>
    </div>
</template>

<script>
export default {
    data() {
        return {
            sortKey: "name",
            vector: -1,
            members: [
                { id: 1, name: "name1", age: 24 },
                { id: 2, name: "name2", age: 18 },
                { id: 3, name: "name3", age: 52 },
                { id: 4, name: "name4", age: 36 },
                { id: 5, name: "name5", age: 20 },
            ],
        };
    },
    computed: {
        editMembers() {
            return this.members.sort((a, b) => {
                return (a[this.sortKey] - b[this.sortKey]) * this.vector;
            });
        },
    },
};
</script>

<style>
</style>