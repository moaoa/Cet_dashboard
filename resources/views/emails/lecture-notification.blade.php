<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إشعار محاضرة جديدة</title>
</head>

<body>
    <h1>إشعار محاضرة جديدة</h1>
    <p>{{ $message }}</p>
    <!-- <p>تفاصيل المحاضرة:</p> -->
    <!-- <ul>
        <li>المادة: {{ $lecture->subject->name }}</li>
        <li>اليوم: {{ $lecture->day_of_week }}</li>
        <li>وقت البدء: {{ $lecture->start_time->format('H:i') }}</li>
        <li>وقت الانتهاء: {{ $lecture->end_time->format('H:i') }}</li>
        <li>رقم القاعة: {{ $lecture->class_room_id }}</li>
    </ul> -->
</body>

</html>