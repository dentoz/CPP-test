import { useToast } from "vue-toastification";
import axios from "axios";
import InviteToast from "../components/InvitationToast.vue";

export function showInviteToast(data) {
    const userData = JSON.parse(localStorage.getItem("token") ?? "{}");
    const toast = useToast();
    toast.info(
        {
            component: InviteToast,
            onClick: () => {}, // prevent dismiss
            props: {
                message: `${data.from_user} invited you to join "${data.topic}"`,
                onAccept: async () => {
                    // Handle join
                    console.log("User accepted the invite");
                    await axios.post(
                        `/api/invitations/${data.invitation_id}/respond`,
                        {
                            status: "accepted",
                        },
                        {
                            headers: {
                                Accept: "application/json",
                                "Content-Type": "application/json",
                                Authorization: `Bearer ${userData.token}`,
                            },
                        }
                    );
                },
                onReject: async () => {
                    // Handle reject
                    console.log("User rejected the invite");
                    await axios.post(
                        `/api/invitations/${data.invitation_id}/respond`,
                        {
                            status: "rejected",
                        },
                        {
                            headers: {
                                Accept: "application/json",
                                "Content-Type": "application/json",
                                Authorization: `Bearer ${userData.token}`,
                            },
                        }
                    );
                },
            },
        },
        {
            position: "top-right",
            timeout: false, // don’t auto-dismiss
            closeOnClick: false,
            draggable: false,
        }
    );
}
