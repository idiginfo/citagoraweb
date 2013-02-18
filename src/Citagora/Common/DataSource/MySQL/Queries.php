<?php

namespace Citagora\Common\DataSource\MySQL;

/**
 * This class contains parameterized Doctrine DBAL SQL queries
 *
 * Note: Search is not implemented
 */
final class Queries
{
    /**
     * This query returns a single document from the database based on its ID
     *
     * :id  int  The document ID
     */
    const GET_DOCUMENT_QUERY = "
                                SELECT c.MYID, c.CREATED, c.UPDATED, c.URI, c.RIGHTS, c.SOURCE, r.TITLE, r.URL, 
                                r.VOLUME, r.PAGESTART, r.PAGEEND, r.KEYWORDS, r.ISSUE, r.ISSUED, r.ISSN, 
                                r.ISBN, r.DOI, r.AUTHORSTRING, j.TITLE as `JTITLE` FROM citagora_objects
                                c INNER JOIN (citagora_references r INNER JOIN citagora_references j ON
                                r.isPartOf = j.MYID) ON c.MYID = r.MYID

                                WHERE r.MYID = :id;
                            ";

    /**
     * This query returns a list of documents from the database based on their IDs
     *
     * Note: For Doctrine DBAL, you must use '\Doctrine\DBAL\Connection::PARAM_INT_ARRAY'
     *       See: http://goo.gl/RdlaC
     *
     * :ids  array  The documents IDs (integers)
     */
    const GET_DOCUMENTS_QUERY = "
                                SELECT c.MYID, c.CREATED, c.UPDATED, c.URI, c.RIGHTS, c.SOURCE, r.TITLE, r.URL, 
                                r.VOLUME, r.PAGESTART, r.PAGEEND, r.KEYWORDS, r.ISSUE, r.ISSUED, r.ISSN, 
                                r.ISBN, r.DOI, r.AUTHORSTRING, j.TITLE as `JTITLE` FROM citagora_objects
                                c INNER JOIN (citagora_references r INNER JOIN citagora_references j ON
                                r.isPartOf = j.MYID) ON c.MYID = r.MYID

                                WHERE r.MYID IN (:ids);
                            ";

    /**
     * This query returns documents that were updated recently
     *
     * :limit     int     Limit
     * :offset    int     Offset
     * :direction string  Either 'ASC' or 'DESC'
     */
    const GET_RECENT_DOCUMENTS_QUERY = "
                                        SELECT c.MYID, c.CREATED, c.UPDATED, c.URI, c.RIGHTS, c.SOURCE, r.TITLE, r.URL, 
                                        r.VOLUME, r.PAGESTART, r.PAGEEND, r.KEYWORDS, r.ISSUE, r.ISSUED, r.ISSN, 
                                        r.ISBN, r.DOI, r.AUTHORSTRING, j.TITLE as `JTITLE` FROM citagora_objects
                                        c INNER JOIN (citagora_references r INNER JOIN citagora_references j ON
                                        r.isPartOf = j.MYID) ON c.MYID = r.MYID

                                        LIMIT :limit OFFSET :offset ORDER BY c.UPDATED :direction;
                                    ";
}

/* EOF: Queries.php */