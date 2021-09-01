<template>
  <div>
    <v-card v-for="task in tasks" :key="task.task_id" class="mb-3">
      <v-list-item @click="onFocusTask(task)" v-ripple class="pl-2 pr-0" style="height:50px;overflow:hidden;">
        <v-list-item-avatar>
          <v-img v-if="task.works.length == 0" src="https://picsum.photos/500/300?image=20" class="rounded-circle"></v-img>
          <v-img v-if="task.works.length == 1" :src="task.works[0].user_img" class="rounded-circle"></v-img>
          <v-img v-if="task.works.length >= 2" src="https://picsum.photos/500/300?image=30" class="rounded-circle"></v-img>
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{task.task_name}}</v-list-item-title>
          <v-list-item-subtitle style="font-size:12px;">
            <span>想定:{{task.task_default_minute}}分</span>
            <span v-if="task.works.length">稼働:{{task.minute}}分</span>
            <span v-if="task.works.length == 1">担当:{{task.works[0].user_name}}</span>
            <span v-if="task.works.length >= 2">担当:複数人</span>
          </v-list-item-subtitle>
        </v-list-item-content>
        <div style="width:40px;height:100%;background-color:gray;opacity:0.3;border-radius:0;"></div>
      </v-list-item>
    </v-card>

    <pre>{{tasks}}</pre>

    <v-dialog v-model="dialog" scrollable>
      <v-card v-if="dialog">
        <v-card-title>{{focusTask.task_name}}</v-card-title>
        <v-divider></v-divider>
        <v-card-text class="pa-3" style="min-height:30vh;">
          <v-card v-for="work in focusTask.works" :key="work.work_id" class="d-flex pa-3 pb-2 mb-4" tile>
            <v-select style="width:46%;" :items="users" v-model="work.user_id" item-value="val" item-text="txt" solo dense></v-select>
            <v-spacer></v-spacer>
            <v-select style="width:46%;" :items="minutes" v-model="work.minute" item-value="val" item-text="txt" solo dense></v-select>
          </v-card>
          <button class="add_btn" @click="addWork()">+</button>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="dialog = false">Close</v-btn>
          <v-btn color="primary" @click="dialog = false">Save</v-btn>
        </v-card-actions>

      </v-card>
    </v-dialog>

  </div>
</template>

<script>
export default {
  data() {
    return {
      dialog: false,
      minutes: [
        { val: 0, txt: "0分" },
        { val: 5, txt: "5分" },
        { val: 10, txt: "10分" },
        { val: 15, txt: "15分" },
        { val: 20, txt: "20分" },
        { val: 25, txt: "25分" },
        { val: 30, txt: "30分" },
      ],
      users: [
        { val: 0, txt: "担当者" },
        { val: 1, txt: "user1" },
        { val: 2, txt: "user2" },
      ],
      focusTask: {},
      tasks: [
        {
          task_id: 1,
          task_name: "task_name1",
          task_default_minute: 5,
          works: [
            {
              work_id: 1,
              user_id: 1,
              user_name: "user1",
              user_img: "https://picsum.photos/500/300?image=10",
              minute: 5,
            },
          ],
        },
        {
          task_id: 2,
          task_name: "task_name2",
          task_default_minute: 10,
          works: [
            {
              work_id: 2,
              user_id: 2,
              user_name: "user2",
              user_img: "https://picsum.photos/500/300?image=1",
              minute: 10,
            },
          ],
        },
        {
          task_id: 3,
          task_name: "task_name3",
          task_default_minute: 15,
          works: [
            {
              work_id: 3,
              user_id: 1,
              user_name: "user1",
              user_img: "https://picsum.photos/500/300?image=10",
              minute: 5,
            },
            {
              work_id: 4,
              user_id: 2,
              user_name: "user2",
              user_img: "https://picsum.photos/500/300?image=1",
              minute: 10,
            },
          ],
        },
        {
          task_id: 4,
          task_name: "task_name4",
          task_default_minute: 5,
          works: [],
        },
      ],
    };
  },
  methods: {
    onFocusTask(task) {
      this.dialog = true;
      for (const [key, value] of Object.entries(task)) {
        if (Array.isArray(value)) {
          let array = [];
          value.forEach((valueObj) => {
            let obj = {};
            for (const [key2, value2] of Object.entries(valueObj)) {
              this.$set(obj, key2, value2);
            }
            array.push(obj);
          });
          this.$set(this.focusTask, key, array);
        } else {
          this.$set(this.focusTask, key, value);
        }
      }
      if (!this.focusTask.works.length) {
        this.focusTask.works.push({
          user_id: 0,
          minute: task.task_default_minute,
        });
      }
    },
    addWork() {
      this.focusTask.works.push({
        user_id: 0,
        minute: 0,
      });
    },
  },
  mounted() {
    this.tasks.forEach((task) => {
      let minute = task.works.reduce(function (sum, work) {
        return sum + work.minute;
      }, 0);
      this.$set(task, "minute", minute);
    });
  },
};
</script>
<style>
* {
  box-sizing: border-box !important;
}
</style>
<style lang="scss" scoped>
p {
  margin: 0;
}
::v-deep {
  .v-text-field__details {
    display: none;
  }
}
.add_btn {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 50px;
  height: 50px;
  font-size: 30px;
  border-radius: 50%;
  margin: 20px auto;
  background-color: black;
  color: white;
}
</style>