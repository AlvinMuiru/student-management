<?php

use yii\db\Migration;

class m250624_063446_create_student_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Students table
        $this->createTable('students', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(50)->notNull(),
            'last_name' => $this->string(50)->notNull(),
            'birthdate' => $this->date(),
            'address' => $this->text(),
            'phone' => $this->string(20),
            'email' => $this->string(100),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        
        // Classes table
        $this->createTable('classes', [
            'id' => $this->primaryKey(),
            'class_name' => $this->string(50)->notNull(),
            'teacher_name' => $this->string(100),
            'schedule' => $this->string(100),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        
        // Class assignments (many-to-many relationship)
        $this->createTable('class_assignments', [
            'id' => $this->primaryKey(),
            'student_id' => $this->integer()->notNull(),
            'class_id' => $this->integer()->notNull(),
            'date_assigned' => $this->date()->notNull(),
        ]);

        // Attendance records
        $this->createTable('attendance', [
            'id' => $this->primaryKey(),
            'student_id' => $this->integer()->notNull(),
            'class_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'status' => $this->string(10)->notNull(), // 'present' or 'absent'
        ]);

        // Foreign keys
        $this->addForeignKey(
            'fk_class_assignment_student',
            'class_assignments',
            'student_id',
            'students',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_class_assignment_class',
            'class_assignments',
            'class_id',
            'classes',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_attendance_student',
            'attendance',
            'student_id',
            'students',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_attendance_class',
            'attendance',
            'class_id',
            'classes',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
        $this->dropTable('attendance');
        $this->dropTable('class_assignments');
        $this->dropTable('classes');
        $this->dropTable('students');
    }

    
}
