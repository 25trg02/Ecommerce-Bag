<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('title', 'LADY BAG - Thời trang túi xách cao cấp')

@section('content')
<div class="container-fluid mt-4">
    <!-- Banner -->
    <div class="jumbotron text-center bg-light mb-4 p-4 rounded">
        <h1 class="display-4">LADY BAG</h1>
        <p class="lead">Bộ sưu tập túi xách cao cấp dành cho phái đẹp</p>
        <hr class="my-4">
        <p>Tự tin - Sang trọng - Đẳng cấp</p>
    </div>

    <!-- Lọc sản phẩm -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('welcome') }}" method="GET" class="form-inline justify-content-center">
                        <div class="form-group mx-2 mb-2">
                            <label for="category" class="mr-2">Danh mục:</label>
                            <select name="category" id="category" class="form-control">
                                <option value="">Tất cả danh mục</option>
                                @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}"
                                    {{ (string)request('category') === (string)$category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tìm kiếm sản phẩm -->
                        <div class="form-group mx-2 mb-2">
                            <label for="search" class="mr-2">Tìm kiếm:</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Nhập tên sản phẩm..." value="{{ request('search') }}">
                        </div>

                        <div class="form-group mx-2 mb-2">
                            <label for="sort" class="mr-2">Sắp xếp:</label>
                            <select name="sort" id="sort" class="form-control">
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mx-2 mb-2">Lọc</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <h2 class="mb-4 text-center">Sản phẩm nổi bật</h2>

    <div class="row">
        @forelse ($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 product-card">
                <div class="product-image">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    @else
                    <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No Image">
                    @endif
                    <div class="product-overlay">
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-light">Xem chi tiết</a>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-center">{{ $product->name }}</h5>
                    <p class="card-text text-truncate">{{ $product->description }}</p>
                    <p class="card-text text-center text-danger font-weight-bold">
                        {{ number_format($product->price, 0, ',', '.') }} VND
                    </p>
                    <p class="card-text text-center">
                        <small class="text-muted">Danh mục: {{ optional($product->category)->name }}</small>
                    </p>
                </div>
                <div class="card-footer text-center bg-white border-top-0">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-block">Xem chi tiết</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">Chưa có sản phẩm phù hợp.</div>
        </div>
        @endforelse
    </div>

    <!-- CSS cho card sản phẩm -->
    <style>
        .product-card {
            transition: all 0.3s;
            border-radius: 8px;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            position: relative;
            overflow: hidden;
            height: 200px;
        }

        .product-image img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            transition: all 0.5s;
        }

        .product-image:hover img {
            transform: scale(1.1);
        }

        .product-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s;
        }

        .product-image:hover .product-overlay {
            opacity: 1;
        }

        /* Khóa kích thước icon phân trang nếu có CSS toàn cục đè svg */
        nav[role="navigation"] svg {
            width: 1rem !important;
            height: 1rem !important;
        }
    </style>

    <!-- Chatbot popup styles & widget -->
    <style>
        #chatbot-toggle-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9998;
            background: #e83e8c;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            font-size: 28px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        #chatbot-popup {
            position: fixed;
            bottom: 90px;
            right: 24px;
            width: 340px;
            max-width: 95vw;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.18);
            z-index: 9999;
            font-size: 15px;
            display: none;
            flex-direction: column;
            overflow: hidden;
            animation: chatbot-pop-in 0.2s;
        }

        @keyframes chatbot-pop-in {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        #chatbot-header {
            background: #e83e8c;
            color: #fff;
            padding: 12px 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #chatbot-messages {
            flex: 1;
            padding: 12px;
            overflow-y: auto;
            background: #faf9fa;
            min-height: 120px;
            max-height: 320px;
        }

        .chatbot-msg {
            margin-bottom: 10px;
            display: flex;
        }

        .chatbot-msg.user {
            justify-content: flex-end;
        }

        .chatbot-msg.bot {
            justify-content: flex-start;
        }

        .chatbot-bubble {
            padding: 8px 14px;
            border-radius: 16px;
            max-width: 80%;
            word-break: break-word;
        }

        .chatbot-msg.user .chatbot-bubble {
            background: #e83e8c;
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .chatbot-msg.bot .chatbot-bubble {
            background: #f1f1f1;
            color: #222;
            border-bottom-left-radius: 4px;
        }

        #chatbot-input-area {
            display: flex;
            border-top: 1px solid #eee;
            background: #fff;
        }

        #chatbot-input {
            flex: 1;
            border: none;
            padding: 10px;
            font-size: 15px;
            outline: none;
        }

        #chatbot-send-btn {
            background: #e83e8c;
            color: #fff;
            border: none;
            padding: 0 18px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 0 0 12px 0;
        }

        #chatbot-close-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
        }

        @media (max-width: 500px) {
            #chatbot-popup {
                width: 98vw;
                right: 1vw;
            }
        }
    </style>

    <button id="chatbot-toggle-btn" title="Tư vấn túi xách">💬</button>
    <div id="chatbot-popup">
        <div id="chatbot-header">
            <span>LadyBag Chatbot</span>
            <button id="chatbot-close-btn" title="Đóng">×</button>
        </div>
        <div id="chatbot-messages"></div>
        <form id="chatbot-input-area" autocomplete="off">
            <input type="text" id="chatbot-input" placeholder="Tư vấn về túi xách nữ..." autocomplete="off" required />
            <button type="submit" id="chatbot-send-btn">Gửi</button>
        </form>
    </div>

    <script>
        // Chatbot popup logic
        const chatbotPopup = document.getElementById('chatbot-popup');
        const chatbotToggleBtn = document.getElementById('chatbot-toggle-btn');
        const chatbotCloseBtn = document.getElementById('chatbot-close-btn');
        const chatbotMessages = document.getElementById('chatbot-messages');
        const chatbotInput = document.getElementById('chatbot-input');
        const chatbotInputArea = document.getElementById('chatbot-input-area');

        function showChatbot() {
            chatbotPopup.style.display = 'flex';
            chatbotToggleBtn.style.display = 'none';
            chatbotInput.focus();
        }

        function hideChatbot() {
            chatbotPopup.style.display = 'none';
            chatbotToggleBtn.style.display = 'flex';
        }
        chatbotToggleBtn.onclick = showChatbot;
        chatbotCloseBtn.onclick = hideChatbot;

        // Hiển thị tin nhắn lên giao diện
        function appendMessage(text, sender = 'bot') {
            const msgDiv = document.createElement('div');
            msgDiv.className = 'chatbot-msg ' + sender;
            const bubble = document.createElement('div');
            bubble.className = 'chatbot-bubble';
            bubble.innerText = text;
            msgDiv.appendChild(bubble);
            chatbotMessages.appendChild(msgDiv);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        // Lời chào mặc định (chỉ hiện 1 lần)
        let greeted = false;

        function greetIfNeeded() {
            if (!greeted) {
                appendMessage('Xin chào! Tôi là LadyBag Chatbot. Hãy hỏi tôi về túi xách nữ hoặc tư vấn sản phẩm phù hợp cho bạn.', 'bot');
                greeted = true;
            }
        }
        chatbotToggleBtn.addEventListener('click', greetIfNeeded);

        // Xử lý gửi tin nhắn
        chatbotInputArea.onsubmit = async function(e) {
            e.preventDefault();
            const userMsg = chatbotInput.value.trim();
            if (!userMsg) return;
            appendMessage(userMsg, 'user');
            chatbotInput.value = '';
            appendMessage('Đang trả lời...', 'bot');

            // Gọi Gemini API
            try {
                const res = await fetch('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyCWuccvFndrLlOr36liGGDtk0QpMWD2Rm0', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        contents: [{
                            parts: [{
                                text: `Bạn là chuyên gia tư vấn bán túi xách nữ. Hãy trả lời người dùng khi khách hàng yêu cầu về 1 thông tin. Yêu cầu: Trả lời như nhân viên bán hàng, chỉ trả lời dạng đoạn văn, KHÔNG dùng dấu *, không dùng bullet, không dùng số đầu dòng, không xuống dòng liên tục, chỉ trả lời đoạn văn tự nhiên.  ${userMsg}`
                            }]
                        }]
                    })
                });
                const data = await res.json();
                // Xóa "Đang trả lời..."
                chatbotMessages.removeChild(chatbotMessages.lastChild);
                let botReply = data?.candidates?.[0]?.content?.parts?.[0]?.text || 'Xin lỗi, tôi chưa có câu trả lời phù hợp.';
                appendMessage(botReply, 'bot');
            } catch (err) {
                chatbotMessages.removeChild(chatbotMessages.lastChild);
                appendMessage('Có lỗi khi kết nối chatbot. Vui lòng thử lại sau.', 'bot');
            }
        };
    </script>

    <!-- Phân trang (Bootstrap) -->
    <div class="d-flex justify-content-center mt-4">
        {{-- Giữ tham số lọc khi đổi trang --}}
        {{ $products->appends([
            'category' => request('category'),
            'sort'     => request('sort'),
        ])->onEachSide(1)->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection