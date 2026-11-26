<?php
/** German labels for IONOS Cloud inventory. */

Dict::Add('DE DE', 'German', 'Deutsch', [
    'Cloud:IONOS' => 'IONOS',
    'Class:Cloud/Attribute:ionos_sec_auth_protection' => 'Zusätzlicher Zugriffsschutz',
    'Class:Cloud/Attribute:ionos_sec_auth_protection+' => 'Gibt an, ob IONOS für dieses virtuelle Datacenter eine zusätzliche Authentifizierung verlangt.',
    'Class:Cloud/Attribute:ionos_sec_auth_protection/Value:unknown' => 'Unbekannt',
    'Class:Cloud/Attribute:ionos_sec_auth_protection/Value:yes' => 'Ja',
    'Class:Cloud/Attribute:ionos_sec_auth_protection/Value:no' => 'Nein',

    'Class:CloudNatGateway/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudNatGatewayLan/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudNatGatewayLanAddress/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudNatGatewayLanAddress/Attribute:ionos_synced_ip_id' => 'Letzter vom IONOS-Collector gesetzter IPAM-Verweis',
    'Class:CloudNatGatewayPublicAddress/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudNatGatewayPublicAddress/Attribute:ionos_synced_ip_id' => 'Letzter vom IONOS-Collector gesetzter IPAM-Verweis',
]);
