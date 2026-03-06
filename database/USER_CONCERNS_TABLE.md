# tbl_user_concerns

User concerns from frontend, linked to customers via `user_id`.

## Table structure (as used)

```sql
CREATE TABLE `tbl_user_concerns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `concern_type` enum('order_status','delivery','product','payment','other') DEFAULT 'other',
  `message` text NOT NULL,
  `contact_preference` enum('phone','email','whatsapp') DEFAULT 'phone',
  `status` enum('pending','in_progress','resolved') DEFAULT 'pending',
  `admin_response` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `status` (`status`)
);
```

- `user_id` – references `users.id` (customer)
- `order_id` – optional, references order (tbl_order_details.id)
- `concern_type` – order_status, delivery, product, payment, other
- `message` – concern body
- `contact_preference` – phone, email, whatsapp
- `status` – pending, in_progress, resolved
- `admin_response` – vendor response to the customer
