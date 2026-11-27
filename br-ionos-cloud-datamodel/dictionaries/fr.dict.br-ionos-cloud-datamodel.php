<?php
/** French labels for IONOS Cloud inventory. */

Dict::Add('FR FR', 'French', 'Français', [
    'Cloud:IONOS' => 'IONOS',
    'Class:VirtualMachine/Attribute:ionos_server_id' => 'Identifiant du serveur IONOS',
    'Class:VirtualMachine/Attribute:ionos_vdc_id' => 'Identifiant du centre de données virtuel IONOS',
    'Class:VirtualMachine/Attribute:ionos_source_key' => 'Source d’importation IONOS',
    'Class:VirtualMachine/Attribute:ionos_server_type' => 'Type de serveur IONOS',
    'Class:Cloud/Attribute:ionos_sec_auth_protection' => 'Protection supplémentaire des accès',
    'Class:Cloud/Attribute:ionos_sec_auth_protection+' => 'Indique si IONOS exige une authentification supplémentaire pour accéder à ce centre de données virtuel.',
    'Class:Cloud/Attribute:ionos_sec_auth_protection/Value:unknown' => 'Inconnue',
    'Class:Cloud/Attribute:ionos_sec_auth_protection/Value:yes' => 'Oui',
    'Class:Cloud/Attribute:ionos_sec_auth_protection/Value:no' => 'Non',

    'Class:CloudNatGateway/Attribute:ionos_source_key' => 'Source d’import IONOS',
    'Class:CloudNatGatewayLan/Attribute:ionos_source_key' => 'Source d’import IONOS',
    'Class:CloudNatGatewayLanAddress/Attribute:ionos_source_key' => 'Source d’import IONOS',
    'Class:CloudNatGatewayLanAddress/Attribute:ionos_synced_ip_id' => 'Dernière référence IPAM définie par le collecteur IONOS',
    'Class:CloudNatGatewayPublicAddress/Attribute:ionos_source_key' => 'Source d’import IONOS',
    'Class:CloudNatGatewayPublicAddress/Attribute:ionos_synced_ip_id' => 'Dernière référence IPAM définie par le collecteur IONOS',
]);
