import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                fetch("/broadcasting/auth", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                    body: JSON.stringify({
                        socket_id: socketId,
                        channel_name: channel.name,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        callback(null, data);
                    })
                    .catch((error) => {
                        callback(error, null);
                    });
            },
        };
    },
});

if (window.location.pathname.includes("/customer/")) {
    const userId = document
        .querySelector('meta[name="user-id"]')
        ?.getAttribute("content");
    if (userId) {
        window.Echo.private(`user.${userId}`).listen(
            "OrderStatusUpdated",
            (e) => {
                console.log("Order status updated:", e);

                if (Notification.permission === "granted") {
                    new Notification("Order Status Updated", {
                        body: `Order #${e.order.id} is now ${e.new_status}`,
                        icon: "/favicon.ico",
                    });
                }

                const orderElement = document.querySelector(
                    `[data-order-id="${e.order.id}"]`
                );
                if (orderElement) {
                    const statusBadge =
                        orderElement.querySelector(".status-badge");
                    if (statusBadge) {
                        statusBadge.textContent =
                            e.new_status.charAt(0).toUpperCase() +
                            e.new_status.slice(1);
                        statusBadge.className = `badge status-badge bg-${
                            e.new_status === "pending"
                                ? "warning"
                                : e.new_status === "shipped"
                                ? "info"
                                : "success"
                        }`;
                    }
                }
            }
        );
    }
}

if (window.location.pathname.includes("/admin/")) {
    window.Echo.join("admin-presence")
        .here((users) => {
            updateOnlineUsers(users);
        })
        .joining((user) => {
            addOnlineUser(user);
        })
        .leaving((user) => {
            removeOnlineUser(user);
        });
}

function updateOnlineUsers(users) {
    const container = document.getElementById("online-users");
    if (container) {
        container.innerHTML = "";
        users.forEach((user) => addOnlineUser(user));
    }
}

function addOnlineUser(user) {
    const container = document.getElementById("online-users");
    if (container) {
        const userElement = document.createElement("div");
        userElement.className = "user-status mb-1";
        userElement.dataset.userId = user.id;
        userElement.innerHTML = `<span class="online-indicator text-success">●</span> <small>${user.name} (${user.role})</small>`;
        container.appendChild(userElement);
    }
}

function removeOnlineUser(user) {
    const userElement = document.querySelector(`[data-user-id="${user.id}"]`);
    if (userElement) {
        userElement.remove();
    }
}
