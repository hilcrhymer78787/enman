import moment from 'moment'
import axios from 'axios';

export const state = () => ({
    users: [],
    tasks: [],
    works: [],
    everydayTasks: [],
})

export const mutations = {
    setUsers(state, users) {
        state.users = users
    },
    setTasks(state, tasks) {
        state.tasks = tasks
    },
    setWorks(state, works) {
        state.works = works
    },
    setEverydayTasks(state, everydayTasks) {
        state.everydayTasks = everydayTasks
    },
}

export const actions = {
    setUsers({ commit }) {
        let users = []
        for (let i = 1; i <= 3; i++) {
            users.push({
                id: i,
                name: 'name' + i,
                email: 'email' + i,
                password: 'password' + i,
                color: 'color' + i,
                room_id: 1,
            });
        }
        commit('setUsers', users)
    },
    async setTasks({ commit }) {
        return axios
            .get("http://localhost:8000/api/task/read")
            .then((res) => {
                commit('setTasks', res.data)
            })
            .catch((err) => {
                alert("エラーです");
            })
    },
    async setWorks({ state, commit }) {
        return axios
            .get("http://localhost:8000/api/work/read")
            .then((res) => {
                commit('setWorks', res.data)
                let works = res.data
                let tasks = state.tasks

                let everydayTasks = []

                tasks = tasks.filter(task => task.is_everyday == 1)
                // tasks.sort((a, b) => {
                //     return (a.works.length - b.works.length)
                // });

                tasks.forEach(task => {
                    task.works = works.filter(work => work.work_task_id == task.task_id);
                    everydayTasks.push(task)
                });
                commit('setEverydayTasks', everydayTasks)
                // console.log(everydayTasks)
            })
            .catch((err) => {
                alert("エラーです");
            })
    },
}