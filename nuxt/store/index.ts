import { GetterTree, ActionTree, MutationTree } from 'vuex'
import { apiWorkReadCalendarRequestType } from '@/types/api/work/read/calendar/request'
import { apiWorkReadAnalyticsResponseType } from '@/types/api/work/read/analytics/response'
import { AxiosRequestConfig, AxiosResponse, AxiosError } from "axios";
import moment from 'moment'
import axios from 'axios'
const CancelToken = axios.CancelToken;
let setCalendarsCancel: any = null;
let setLoginInfoByTokenCancel: any = null;
export type RootState = ReturnType<typeof state>

export const state = () => ({
    loginInfo: null,
    calendars: [],
})
export const mutations: MutationTree<RootState> = {
    setLoginInfo(state, loginInfo) {
        state.loginInfo = loginInfo
    },
    setCalendars(state, calendars) {
        state.calendars = calendars
    },
}

export const actions: ActionTree<RootState, RootState> = {
    setTokenRedirect({ }, token) {
        this.$cookies.set("token", token, {
            maxAge: 60 * 60 * 24 * 30,
        });
        this.$router.push("/");
    },
    setLoginInfoByToken({ commit, dispatch }) {
        if (setLoginInfoByTokenCancel) {
            setLoginInfoByTokenCancel()
        }
        this.$axios.get(`/api/user/bearer_authentication`, {
            cancelToken: new CancelToken(c => {
                setLoginInfoByTokenCancel = c
            }),
        })
            .then((res) => {
                // トークンが有効
                if (this.$cookies.get("token")) {
                    if ((this.$router.currentRoute.name == 'login' || this.$router.currentRoute.name == 'login-newUser')) {
                        this.$router.push("/");
                    }
                    commit('setLoginInfo', res.data)
                }
            })
            .catch((err) => {
                if (err.response) {
                    dispatch('logout')
                }
                if (err.response && err.response.status == 429) {
                    alert('一定時間にアクセスが集中したため、しばらくアクセスできません')
                }
            })
    },
    logout({ commit }) {
        this.$cookies.remove("token");
        if (!(this.$router.currentRoute.name == 'login' || this.$router.currentRoute.name == 'login-newUser')) {
            this.$router.push("/login");
        }
        commit('setLoginInfo', false)
    },
    async setCalendars({ commit }) {
        if (setCalendarsCancel) {
            setCalendarsCancel()
        }
        let apiParam: apiWorkReadCalendarRequestType = {
            year: Number(this.$router.currentRoute.query.year),
            month: Number(this.$router.currentRoute.query.month)
        }
        await this.$axios
            .get(`/api/work/read/calendar`, {
                params: apiParam,
                cancelToken: new CancelToken(c => {
                    setCalendarsCancel = c
                }),
            })
            .then((res:AxiosResponse) => {
                commit("setCalendars", res.data.calendars);
            })
            .catch((err:AxiosError) => {
                console.log(err.response)
            })
    },
}