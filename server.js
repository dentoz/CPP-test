const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const bodyParser = require("body-parser");

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*", // Allow frontend dev origin
        methods: ["GET", "POST"],
    },
});

app.use(bodyParser.json());

io.on("connection", (socket) => {
    console.log(`🔌 New client connected: ${socket.id}`);

    socket.on("join-room", (roomId) => {
        socket.join(roomId);
        console.log(`🧩 Joined room: ${roomId}`);
    });

    // socket.on("send-message", (data) => {
    //     const { roomId, message, user } = data;
    //     console.log("boom", data);
    //     io.to(roomId).emit("new-message", { message, user });
    // });

    socket.on("disconnect", () => {
        console.log(`❌ Disconnected: ${socket.id}`);
    });
});

app.post("/join", (req, res) => {
    const { roomId, user } = req.body;
    io.to(roomId).emit("user-invited", {
        roomId,
        user,
    });

    res.status(200).json({ status: "user invited" });
});

app.post("/send-message", (req, res) => {
    const { chat_room_id: roomId, user, message, id } = req.body;
    console.log('sss', req.body)
    io.to(`chat.room.${roomId}`).emit("new-message", {
        id,
        user,
        message,
    });

    res.status(200).json({ status: "Message sent" });
});

server.listen(3000, () => {
    console.log("🟢 Socket.IO server running on port 3000");
});
