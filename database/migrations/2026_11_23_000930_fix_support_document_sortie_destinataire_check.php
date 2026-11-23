<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE support_documents_stocks
            DROP CHECK chk_support_document_sortie_un_destinataire
        ");

        DB::statement("
            ALTER TABLE support_documents_stocks
            ADD CONSTRAINT chk_support_document_sortie_un_destinataire
            CHECK (
                type_document <> 'SORTIE'
                OR (
                    (employee_id IS NOT NULL AND direction_id IS NULL AND antenne_id IS NULL)
                    OR (employee_id IS NULL AND direction_id IS NOT NULL AND antenne_id IS NULL)
                    OR (employee_id IS NULL AND direction_id IS NULL AND antenne_id IS NOT NULL)
                )
            )
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE support_documents_stocks
            DROP CHECK chk_support_document_sortie_un_destinataire
        ");

        DB::statement("
            ALTER TABLE support_documents_stocks
            ADD CONSTRAINT chk_support_document_sortie_un_destinataire
            CHECK (
                type_document <> 'SORTIE'
                OR (
                    (employee_id IS NOT NULL AND direction_id IS NULL AND antenne_id IS NULL AND support_ticket_id IS NULL)
                    OR (employee_id IS NULL AND direction_id IS NOT NULL AND antenne_id IS NULL AND support_ticket_id IS NULL)
                    OR (employee_id IS NULL AND direction_id IS NULL AND antenne_id IS NOT NULL AND support_ticket_id IS NULL)
                    OR (employee_id IS NULL AND direction_id IS NULL AND antenne_id IS NULL AND support_ticket_id IS NOT NULL)
                )
            )
        ");
    }
};
